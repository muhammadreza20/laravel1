<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // Ambil kata kunci pencarian dari input form
        $search = $request->input('search');

        // Query untuk mengambil data pengguna dengan relasi peran (role)
        $query = User::with('role');

        // Jika ada kata kunci pencarian, tambahkan kondisi untuk mencari berdasarkan nama atau peran
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhereHas('role', function ($r) use ($search) {
                        $r->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        // Ambil data pengguna berdasarkan hasil query
        $db = $query->paginate(4);

        // Ambil daftar peran (roles)
        $role = Roles::all();

        // Kembalikan data pengguna dan daftar peran ke halaman dashboard
        return view('dashboard', compact('db', 'role', 'search'));
    }

    public function create()
    {
        $role = DB::table('roles')->get();
        return view('user.create', compact('role'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'role_id' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'Nama Lengkap harus diisi!!',
                'role_id.required' => 'Role harus diisi!!',
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
            'role_id' => $request->role_id,
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
        $user = User::with('role')->find($id);
        $role = DB::table('roles')->get();
        return view('user.edit', compact('user', 'role'));
    }
    // REplace with your own code
    public function getUserData($id)
    {
        // Mengambil data pengguna berdasarkan ID yang diberikan
        $user = User::with('role')->find($id);
        $role = $user->role->name;

        // Kirim data pengguna sebagai respons JSON
        return response()->json(['status' => 'success', 'data' => $user, 'role_name' => $role]);
    }


    public function update(Request $request)
    {
        $user = User::findOrFail($request->id); // Menggunakan $request->user_id

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        // Update data user
        $user->name = $request->name;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Redirect ke halaman yang sesuai dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Data User Berhasil Diperbarui');
    }


    // Rols
    public function createroles(Request $request): RedirectResponse
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
            return redirect('dashboard')->with('success', 'Role Created Successfully!');
        } else {
            return Redirect::back()->with('error', 'Gagal membuat role');
        }
    }
}
