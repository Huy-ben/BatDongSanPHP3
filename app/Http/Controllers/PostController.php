<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function detail()
    {
        return inertia('Client/PostDetail');
    }
}
