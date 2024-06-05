<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function index()
    {
        $db = Roles::paginate(3);
        return view('role.index', compact('db'));
    }

    public function create(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'unique:roles'],
            ],
            [
                'name.required' => 'Nama Role harus diisi!!',
                'name.string' => 'Nama Role harus tipe data string!!',
                'name.max' => 'Nama Role tidak boleh lebih dari 255 karakter!!',
                'name.regex' => 'Hanya boleh mengandung huruf dan spasi!!',
                'name.unique' => 'Role sudah ada!!',
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
        Roles::create($validated);

        if ($validated) {
            return redirect('datarole')->with('success', 'Role Created Successfully!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat role.');
        }
    }

    public function edit($id)
    {
        $db = Roles::find($id);
        return view('role.edit', compact('db'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'nullable|string|regex:/^[a-zA-Z\s]+$/|max:255',
            ],
            [
                'name.string' => 'Nama Role harus tipe data string!!',
                'name.max' => 'Nama Role tidak boleh lebih dari 255 karakter!!',
                'name.regex' => 'Hanya boleh mengandung huruf dan spasi!!',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        $rols = Roles::find($id);

        // If the rols doesn't exist, return 404 error
        if (!$rols) {
            abort(404);
        }

        // Update the rols attributes with validated data
        $rols->update($validated);

        return redirect('datarole')->with('success', 'Role Updated Successfully!');
    }

    public function delete($id, Request $request)
    {
        $currentPage = $request->query('page', 1);
        // Temukan data "rols" berdasarkan ID
        $rols = Roles::find($id);

        // Pastikan data ditemukan
        if (!$rols) {
            return redirect()->route('datarole')->with('error', 'Role Not Found!');
        }

        // Hapus data "rols"
        $rols->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('role', ['page' => $currentPage])->with('success', 'Role deleted successfully');
    }
}
