<?php

namespace App\Http\Controllers;

use App\Models\ListingPackage;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const TRIAL_PACKAGE = [
        'price' => 0,
        'name' => 'Trial',
        'type' => '0',
        'months' => 1,
        'is_featured' => false,
    ];

    private const PACKAGE_MAP = [
        'vip' => [
            'price' => 179000,
            'name' => 'VIP',
            'type' => '1',
            'months' => 3,
        ],
        'svip' => [
            'price' => 349000,
            'name' => 'SVIP',
            'type' => '2',
            'months' => 3,
        ],
    ];

    public function create(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'in:vip,svip'],
            'renew' => ['nullable', 'boolean'],
        ]);

        $isRenew = (bool) ($validated['renew'] ?? false);

        $tmnCode = config('services.vnpay.tmn_code');
        $hashSecret = config('services.vnpay.hash_secret');
        $vnpUrl = config('services.vnpay.url');

        if (! $tmnCode || ! $hashSecret || ! $vnpUrl) {
            return redirect('/thanh-toan?plan='.$validated['plan'].'&status=failed&reason=config');
        }

        $package = self::PACKAGE_MAP[$validated['plan']];

        if ($isRenew) {
            $renewablePackage = ListingPackage::query()
                ->where('user_id', $request->user()->id)
                ->where('status', '1')
                ->where('package_type', $package['type'])
                ->whereDate('expiry_date', '>=', now()->toDateString())
                ->orderByDesc('expiry_date')
                ->first();

            if (! $renewablePackage) {
                return $this->redirectWithResult($request, [
                    'status' => 'failed',
                    'reason' => 'renew_invalid',
                    'plan' => $validated['plan'],
                    'renew' => true,
                ]);
            }
        }

        $txnRef = now()->format('YmdHis').$request->user()->id.random_int(1000, 9999);

        $vnpData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => $package['price'] * 100,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $request->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan goi '.$package['name'],
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => route('payment.vnpay.return'),
            'vnp_TxnRef' => $txnRef,
        ];

        ksort($vnpData);

        $query = [];
        foreach ($vnpData as $key => $value) {
            $query[] = urlencode((string) $key).'='.urlencode((string) $value);
        }

        $hashData = implode('&', $query);
        $vnpData['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $hashSecret);

        $request->session()->put('vnpay_pending.'.$txnRef, [
            'user_id' => $request->user()->id,
            'plan' => $validated['plan'],
            'amount' => $package['price'],
            'renew' => $isRenew,
        ]);

        $paymentUrl = $vnpUrl.'?'.http_build_query($vnpData);

        return Inertia::location($paymentUrl);
    }

    public function activateTrial(Request $request): RedirectResponse
    {
        $existingTrial = ListingPackage::query()
            ->where('user_id', $request->user()->id)
            ->where('package_type', self::TRIAL_PACKAGE['type'])
            ->where('status', '1')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->exists();

        if ($existingTrial) {
            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'trial_exists',
                'plan' => 'trial',
                'renew' => false,
            ]);
        }

        ListingPackage::create([
            'user_id' => $request->user()->id,
            'description' => 'Goi Trial duoc kich hoat.',
            'price' => self::TRIAL_PACKAGE['price'],
            'expiry_date' => now()->addMonthsNoOverflow(self::TRIAL_PACKAGE['months'])->toDateString(),
            'package_type' => self::TRIAL_PACKAGE['type'],
            'package_name' => self::TRIAL_PACKAGE['name'],
            'status' => '1',
            'is_featured' => self::TRIAL_PACKAGE['is_featured'],
        ]);

        return $this->redirectWithResult($request, [
            'status' => 'success',
            'plan' => 'trial',
            'renew' => false,
        ]);
    }

    public function callback(Request $request): RedirectResponse
    {
        $tmnCode = config('services.vnpay.tmn_code');
        $hashSecret = config('services.vnpay.hash_secret');

        if (! $tmnCode || ! $hashSecret) {
            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'config',
            ]);
        }

        $input = $request->query();
        $vnpSecureHash = $input['vnp_SecureHash'] ?? null;
        unset($input['vnp_SecureHash'], $input['vnp_SecureHashType']);

        ksort($input);
        $query = [];
        foreach ($input as $key => $value) {
            $query[] = urlencode((string) $key).'='.urlencode((string) $value);
        }

        $validHash = hash_hmac('sha512', implode('&', $query), $hashSecret);

        if (! $vnpSecureHash || ! hash_equals($validHash, $vnpSecureHash)) {
            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'signature',
            ]);
        }

        $txnRef = (string) ($request->query('vnp_TxnRef', ''));
        $transactionCode = (string) ($request->query('vnp_TransactionNo', $txnRef));
        $responseCode = (string) ($request->query('vnp_ResponseCode', ''));
        $transactionStatus = (string) ($request->query('vnp_TransactionStatus', ''));
        $amount = (int) $request->query('vnp_Amount', 0);

        $pendingKey = 'vnpay_pending.'.$txnRef;
        $pending = $request->session()->get($pendingKey);

        if (! $pending) {
            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'session',
                'txnRef' => $txnRef,
            ]);
        }

        if ($amount !== ((int) $pending['amount']) * 100) {
            $request->session()->forget($pendingKey);

            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'amount',
                'plan' => $pending['plan'],
                'txnRef' => $txnRef,
                'renew' => (bool) ($pending['renew'] ?? false),
            ]);
        }

        if ($responseCode !== '00' || $transactionStatus !== '00') {
            $request->session()->forget($pendingKey);

            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'payment',
                'plan' => $pending['plan'],
                'txnRef' => $txnRef,
                'renew' => (bool) ($pending['renew'] ?? false),
            ]);
        }

        $package = self::PACKAGE_MAP[$pending['plan']] ?? null;
        if (! $package) {
            $request->session()->forget($pendingKey);

            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'plan',
                'txnRef' => $txnRef,
            ]);
        }

        try {
            if (! empty($pending['renew'])) {
                $renewablePackage = ListingPackage::query()
                    ->where('user_id', $pending['user_id'])
                    ->where('status', '1')
                    ->where('package_type', $package['type'])
                    ->whereDate('expiry_date', '>=', now()->toDateString())
                    ->orderByDesc('expiry_date')
                    ->first();

                if (! $renewablePackage) {
                    $request->session()->forget($pendingKey);

                    return $this->redirectWithResult($request, [
                        'status' => 'failed',
                        'reason' => 'renew_invalid',
                        'plan' => $pending['plan'],
                        'txnRef' => $txnRef,
                        'renew' => true,
                    ]);
                }

                $expiryDate = $renewablePackage->expiry_date
                    ? Carbon::parse((string) $renewablePackage->expiry_date)
                    : null;

                $baseExpiry = $expiryDate && $expiryDate->isFuture()
                    ? $expiryDate
                    : now();

                $renewablePackage->update([
                    'description' => 'Gia han goi '.$package['name'].' qua VNPay. Ma GD: '.$txnRef,
                    'price' => $package['price'],
                    'expiry_date' => $baseExpiry->copy()->addMonthsNoOverflow($package['months'])->toDateString(),
                    'status' => '1',
                    'is_featured' => true,
                ]);

                $this->createPackagePaymentNotification(
                    (int) $pending['user_id'],
                    (string) $package['name'],
                    $txnRef,
                    true
                );
            } else {
                ListingPackage::create([
                    'user_id' => $pending['user_id'],
                    'description' => 'Goi '.$package['name'].' thanh toan qua VNPay. Ma GD: '.$txnRef,
                    'price' => $package['price'],
                    'expiry_date' => now()->addMonthsNoOverflow($package['months'])->toDateString(),
                    'package_type' => $package['type'],
                    'package_name' => $package['name'],
                    'status' => '1',
                    'is_featured' => true,
                ]);

                $this->createPackagePaymentNotification(
                    (int) $pending['user_id'],
                    (string) $package['name'],
                    $txnRef,
                    false
                );
            }

            $this->createOrderRecord(
                (int) $pending['user_id'],
                (string) $package['name'],
                (float) $pending['amount'],
                ! empty($pending['renew']),
                $transactionCode
            );
        } catch (\Throwable $exception) {
            Log::error('VNPay callback save package failed', [
                'message' => $exception->getMessage(),
                'txn_ref' => $txnRef,
                'user_id' => $pending['user_id'],
            ]);

            $request->session()->forget($pendingKey);

            return $this->redirectWithResult($request, [
                'status' => 'failed',
                'reason' => 'save',
                'plan' => $pending['plan'],
                'txnRef' => $txnRef,
                'renew' => (bool) ($pending['renew'] ?? false),
            ]);
        }

        $request->session()->forget($pendingKey);

        return $this->redirectWithResult($request, [
            'status' => 'success',
            'plan' => $pending['plan'],
            'txnRef' => $txnRef,
            'renew' => (bool) ($pending['renew'] ?? false),
        ]);
    }

    public function result(Request $request): \Inertia\Response|RedirectResponse
    {
        $result = $request->session()->get('payment_result');

        if (! $result) {
            return redirect('/');
        }

        return Inertia::render('Client/PaymentResult', $result);
    }

    public function consumeResult(Request $request): JsonResponse
    {
        $request->session()->forget('payment_result');

        return response()->json(['ok' => true]);
    }

    private function redirectWithResult(Request $request, array $payload): RedirectResponse
    {
        $request->session()->put('payment_result', [
            'status' => $payload['status'] ?? 'failed',
            'reason' => $payload['reason'] ?? '',
            'plan' => $payload['plan'] ?? '',
            'txnRef' => $payload['txnRef'] ?? '',
            'renew' => (bool) ($payload['renew'] ?? false),
        ]);

        return redirect()->route('payment.result');
    }

    private function createPackagePaymentNotification(int $userId, string $packageName, string $txnRef, bool $isRenew): void
    {
        $title = $isRenew
            ? 'Gia hạn gói '.$packageName.' thành công'
            : 'Bạn đã trở thành '.$packageName;

        $content = $isRenew
            ? 'Bạn đã gia hạn thành công gói '.$packageName.'. Mã giao dịch: '.$txnRef.'.'
            : 'Thanh toán thành công. Tài khoản của bạn đã được nâng cấp lên '.$packageName.'. Mã giao dịch: '.$txnRef.'.';

        Notification::query()->create([
            'user_id' => $userId,
            'title' => $title,
            'content' => $content,
            'is_read' => false,
        ]);
    }

    private function createOrderRecord(int $userId, string $packageName, float $amount, bool $isRenew, string $transactionCode): void
    {
        Order::query()->create([
            'user_id' => $userId,
            'type' => $isRenew ? 'renew' : 'new',
            'package_name' => $packageName,
            'amount' => $amount,
            'transaction_code' => $transactionCode,
        ]);
    }
}
