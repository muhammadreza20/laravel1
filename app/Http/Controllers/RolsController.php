<?php

namespace App\Http\Controllers;

use App\Models\Rols;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class RolsController extends Controller
{
    public function index()
    {
        $db = Rols::all();
        return view('rols.index', compact('db'));
    }

    public function create(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name_rols' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            ],
            [
                'name_rols.required' => 'Nama Role harus diisi!!',
                'name_rols.string' => 'Nama Role harus tipe data string!!',
                'name_rols.max' => 'Nama Role tidak boleh lebih dari 255 karakter!!',
                'name_rols.regex' => 'Hanya boleh mengandung huruf dan spasi!!'
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
        Rols::create($validated);

        if ($validated) {
            return redirect('datarols')->with('success', 'Rols created successfully!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat rols.');
        }
    }

    public function edit($id)
    {
        $db = Rols::find($id);
        return view('rols.edit', compact('db'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_rols' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        $rols = Rols::find($id);

        // If the rols doesn't exist, return 404 error
        if (!$rols) {
            abort(404);
        }

        // Update the rols attributes with validated data
        $rols->update($validated);

        return redirect('datarols')->with('success', 'Rols created successfully!');
    }

    public function delete($id)
    {
        // Temukan data "rols" berdasarkan ID
        $rols = Rols::find($id);

        // Pastikan data ditemukan
        if (!$rols) {
            return redirect()->route('datarols')->with('error', 'Rols not found!');
        }

        // Hapus data "rols"
        $rols->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('rols')->with('success', 'Rols deleted successfully!');
    }
}
