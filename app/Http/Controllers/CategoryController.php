<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $db = Category::with('product')->paginate(4);
        return view('category.index', compact('db'));
    }

    public function create(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z_-]+$/'],
            ],
            [
                'name.required' => 'Categori harus diisi!!',
                'name.string' => 'Categori harus tipe data string!!',
                'name.max' => 'Categori tidak boleh lebih dari 255 karakter!!',
                'slug.required' => 'Slug harus diisi!!',
                'slug.string' => 'Slug harus tipe data string!!',
                'slug.max' => 'Slug tidak boleh lebih dari 255 karakter!!',
                'slug.regex' => 'Hanya boleh mengandung huruf, tanda hubung (-), dan garis bawah (_)!!',
                'slug.unique' => 'Slug sudah ada!!',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        // Create the post...
        Category::create($validated);

        if ($validated) {
            return redirect('categorys')->with('success', 'Category Created Successfully!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat Category');
        }
    }
}
