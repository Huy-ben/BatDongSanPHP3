<?php

namespace App\Http\Controllers;

use App\Models\ListingPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __invoke(Request $request)
    {
        $hasUsedTrial = false;

        if ($request->user()) {
            $hasUsedTrial = ListingPackage::query()
                ->where('user_id', $request->user()->id)
                ->where('package_type', '0')
                ->exists();
        }

        return inertia('Client/Package', [
            'hasUsedTrial' => $hasUsedTrial,
        ]);
    }
}