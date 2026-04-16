<?php

namespace App\Console\Commands;

use App\Models\ListingPackage;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyExpiringListingPackages extends Command
{
    protected $signature = 'packages:notify-expiring';

    protected $description = 'Create notifications for listing packages expiring in 10 days';

    public function handle(): int
    {
        $targetDate = Carbon::today()->addDays(10);
        $createdCount = 0;

        ListingPackage::query()
            ->where('status', '1')
            ->whereDate('expiry_date', $targetDate)
            ->chunkById(100, function ($packages) use (&$createdCount): void {
                foreach ($packages as $package) {
                    $content = sprintf(
                        "Gói đăng tin '%s' của bạn sẽ hết hạn vào ngày %s. Vui lòng gia hạn để không bị gián đoạn hiển thị.",
                        $package->package_name,
                        optional($package->expiry_date)->format('d/m/Y') ?? ''
                    );

                    $alreadyNotified = Notification::query()
                        ->where('user_id', $package->user_id)
                        ->where('title', 'Nhắc gia hạn gói đăng tin')
                        ->where('content', $content)
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    Notification::query()->create([
                        'user_id' => $package->user_id,
                        'title' => 'Nhắc gia hạn gói đăng tin',
                        'content' => $content,
                        'is_read' => false,
                    ]);

                    $createdCount++;
                }
            });

        $this->info("Created {$createdCount} expiring package notification(s).");

        return self::SUCCESS;
    }
}
