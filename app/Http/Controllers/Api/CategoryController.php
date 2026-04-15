<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    public function index(){
            $categories = DB::table('categories')
                ->whereNotNull('parent_id')
                ->whereNotNull('image')
                ->get();
            return response()->json($categories);
    }
}
