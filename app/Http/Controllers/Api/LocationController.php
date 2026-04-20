<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function provinces()
    {
        $provinces = Cache::remember('locations.provinces', now()->addDay(), function () {
            $response = Http::timeout(8)
                ->retry(2, 200)
                ->get('https://provinces.open-api.vn/api/p/');

            if (! $response->ok()) {
                return [];
            }

            $data = $response->json();

            if (! is_array($data)) {
                return [];
            }

            return collect($data)
                ->map(function ($province) {
                    $name = trim((string) ($province['name'] ?? ''));

                    if ($name === '') {
                        return null;
                    }

                    return [
                        'code' => (string) ($province['code'] ?? ''),
                        'name' => $name,
                    ];
                })
                ->filter()
                ->values()
                ->all();
        });

        return response()->json([
            'provinces' => $provinces,
        ]);
    }
}
