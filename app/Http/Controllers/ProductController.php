<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $db = Product::with('user', 'category')->paginate(4);
        return view('product.index');
    }
}
