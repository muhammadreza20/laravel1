<?php

namespace App\Http\Controllers;

use App\Models\Rols;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function index()
    {
        $db = User::with('rols')->get();
        $rols = DB::table('rols')->get();
        return view('dashboard', compact('db', 'rols'));
    }
    public function create()
    {
        $rols = DB::table('rols')->get();
        return view('user.create', compact('rols'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'rols_id' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'Nama Lengkap harus diisi!!',
                'rols_id.required' => 'Role harus diisi!!',
                'name.max' => 'Nama tidak boleh lebih dari 255 character!!',
                'email.required' => 'Email harus diisi!!',
                'email.email' => 'Email harus berformat email yang valid!!',
                'email.max' => 'Email tidak boleh lebih dari 255 character!!',
                'email.unique' => 'Email sudah digunakan oleh pengguna lain',
                'password.required' => 'Password harus diisi!!',
                'password.min' => 'Password harus terdiri dari minimal 8 character!!',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'rols_id' => $request->rols_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect('dashboard')->with('success', 'User Created Successfully!!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat User');
        }
    }

    public function delete($id)
    {
        // Mendapatkan pengguna yang sedang login
        $currentUser = auth()->user();

        // Mencari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Mengecek apakah pengguna yang ingin dihapus adalah pengguna yang sedang login
        if ($currentUser->id == $user->id) {
            return redirect()->back()->with('error', 'Tidak Dapat Menghapus User Yang Sedang Login!!');
        }

        // Mengecek apakah pengguna sedang aktif
        if ($user->active) {
            return redirect()->back()->with('error', 'Tidak Dapat Menghapus User Yang Sedang Aktif!!');
        }

        // Menghapus pengguna jika tidak aktif dan bukan pengguna yang sedang login
        $user->delete();

        // Mengembalikan pesan sukses setelah pengguna berhasil dihapus
        return redirect()->route('dashboard')->with('success', 'User Deleted Successfully!!');
    }

    public function edit($id)
    {
        $user = User::with('rols')->find($id);
        $rols = DB::table('rols')->get();
        return view('user.edit', compact('user', 'rols'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang masuk (sesuaikan dengan kebutuhan)
        $request->validate([
            'name' => 'nullable|string|max:255',
            'rols_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        // Ambil data pengguna dari database
        $user = User::findOrFail($id);

        // Perbarui hanya field yang diisi
        $user->name = $request->input('name') ?: $user->name;
        $user->rols_id = $request->input('rols_id') ?: $user->rols_id;
        $user->email = $request->input('email') ?: $user->email;

        // Jika password diisi, enkripsi dan perbarui
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman yang sesuai dengan pesan sukses
        return redirect()->route('dashboard', $id)->with('success', 'Data User Berhasil Diperbarui');
    }

    // Rols

    public function addrols(Request $request): RedirectResponse
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
            return redirect('dashboard')->with('success', 'Role Created Successfully!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat role');
        }
    }
}
