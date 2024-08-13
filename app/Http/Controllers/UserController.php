<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Cabang;
use DB;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
    
        $users = User::where('role', '!=', 'admin')->get();
        foreach ($users as $user) {
            $user->status_vote = $this->checkVoteStatus($user->id);
        }
    
        return view('admin.manajemenuser', compact('users'));
    }
    
    private function checkVoteStatus($id)
    {
        $hasVotedPusat = \DB::table('votes')->where('iduser', $id)->exists();
        $hasVotedDaerah = \DB::table('votedaerah')->where('iduser', $id)->exists();
    
        return $hasVotedPusat && $hasVotedDaerah ? 'Done' : 'Not Done';
    }

    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $cabangs = Cabang::all();
        return view('admin.manajemenusercreate', compact('cabangs'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'NIK' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'idcabang' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        $this->storeUser($request->all());

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        $cabangs = Cabang::all();
        return view('admin.manajemenuseredit', compact('user', 'cabangs'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'NIK' => ['required', 'string', 'max:255', 'unique:users,NIK,' . $user->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'idcabang' => ['required', 'integer'],
            'pusat' => ['required', 'in:yes,no'], 
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $data = [
            'NIK' => $request->NIK,
            'name' => $request->name,
            'email' => $request->email,
            'idcabang' => $request->idcabang,
            'pusat' => $request->pusat, 
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }

    protected function storeUser(array $data)
    {
        return User::create([
            'NIK' => $data['NIK'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('00000000'),
            'role' => 'karyawan',
            'idcabang' => $data['idcabang'],
            'pusat' => 'no', 
        ]);
    }
}
