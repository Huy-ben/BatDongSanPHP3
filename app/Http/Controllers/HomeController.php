<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return inertia('Client/Home');
    }

    public function setPreferredLocation(Request $request)
    {
        $validated = $request->validate([
            'preferred_location' => ['required', 'string', 'max:120'],
        ]);

        $preferredLocation = trim((string) $validated['preferred_location']);

        $request->session()->put('home.preferred_location', $preferredLocation);

        return response()->json([
            'preferred_location' => $preferredLocation,
        ]);
    }
}
