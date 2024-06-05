<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
            'price' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Menambahkan validasi untuk gambar
            'category_id' => ['required', 'integer'],
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Deskripsi harus diisi!',
            'price.required' => 'Harga harus diisi!',
            'image.required' => 'Gambar harus diisi!',
            'image.image' => 'File harus berupa gambar!',
            'category_id.required' => 'Kategori harus diisi!',
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

        // Konversi input harga menjadi integer
        $price = (int) $request->input('price');

        // Membuat produk
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $price,
            'image' => $fileName ?? null, // Jika file gambar gagal disimpan, set nilai default ke null
            'users_id' => $request->input('users_id'),
            'category_id' => $request->input('category_id'),
        ]);

        if ($product) {
            return redirect('products')->with('success', 'Product Created Successfully!');
        } else {
            return redirect()->back()->with('error', 'Gagal membuat Product');
        }
    }


    public function edit($id)
    {
        $db = Product::with('user', 'category')->find($id);
        $category = Category::all();
        return view('product.edit', compact('db', 'category'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'users_id' => ['nullable'],
            'category_id' => ['nullable', 'integer'],
        ], [
            'name.string' => 'Nama harus berupa teks!',
            'description.string' => 'Deskripsi harus berupa teks!',
            'price.numeric' => 'Harga harus berupa angka!',
            'image.image' => 'File harus berupa gambar!',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif!',
            'image.max' => 'Ukuran gambar maksimal 2MB!',
            'category_id.integer' => 'Kategori harus berupa angka!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil data produk dari database
        $product = Product::findOrFail($id);

        // Perbarui field yang diisi
        if ($request->filled('name')) {
            $product->name = $request->input('name');
        }

        if ($request->filled('description')) {
            $product->description = $request->input('description');
        }

        if ($request->filled('price')) {
            // Konversi input harga menjadi integer
            $price = (int) $request->input('price');
            $product->price = $price;
        }

        if ($request->filled('users_id')) {
            $product->users_id = $request->input('users_id');
        }

        if ($request->filled('category_id')) {
            $product->category_id = $request->input('category_id');
        }

        // Jika ada file gambar yang diunggah, perbarui gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete($product->image);
            }

            // Simpan gambar baru
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('product/img'), $fileName);
            $product->image = $fileName;
        }

        // Simpan perubahan
        $product->save();

        return redirect()->route('dataproducts')->with('success', 'Data Product Berhasil Diperbarui');
    }

    public function delete($id)
    {
        // Temukan data "rols" berdasarkan ID
        $rols = Product::find($id);

        // Pastikan data ditemukan
        if (!$rols) {
            return redirect()->route('dataproducts')->with('error', 'Product NOT Found!');
        }

        // Hapus data "rols"
        $rols->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('dataproducts')->with('success', 'Product Deleted Successfully!');
    }
}
