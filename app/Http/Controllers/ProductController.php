<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $db = Product::with('user', 'category')->paginate(4);
        $category = Category::all();
        return view('product.index', compact('db', 'category'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Menambahkan validasi untuk gambar
            'category_id' => ['required'],
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.string' => 'Nama harus berupa teks!',
            'image.required' => 'Gambar harus diisi!',
            'image.image' => 'File harus berupa gambar!',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif!',
            'image.max' => 'Ukuran gambar maksimal 2MB!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('product/img'), $fileName);
            } else {
                return redirect()->back()->with('error', 'File gambar tidak valid.');
            }
        } else {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        // Membuat produk
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $fileName ?? null, // Jika file gambar gagal disimpan, set nilai default ke null
            'users_id' => $request->users_id,
            'category_id' => $request->category_id,
        ]);


        if ($product) {
            return redirect('products')->with('success', 'Product Created Successfully!');
        } else {
            return redirect()->back()->with('error', 'Gagal membuat Product');
        }
    }
}
