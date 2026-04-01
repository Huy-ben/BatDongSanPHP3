<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function data()
    {
        $posts = DB::table('products')->get();
        return response()->json($posts);
    }
}
