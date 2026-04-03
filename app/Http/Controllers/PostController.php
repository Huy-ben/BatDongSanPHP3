<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function postsell()
    {
        return inertia('Client/PostSell');
    }
    public function postrent()
    {
        return inertia('Client/PostRent');
    }
    public function detail()
    {
        return inertia('Client/PostDetail');
    }
}
