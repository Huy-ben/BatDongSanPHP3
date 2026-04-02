<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        return inertia('Client/Post');
    }
    public function detail()
    {
        return inertia('Client/PostDetail');
    }
}
