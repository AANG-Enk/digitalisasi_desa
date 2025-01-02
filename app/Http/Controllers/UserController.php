<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('roles',function($q){
            $q->where('name','!=','Warga');
        })->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('users.store');
        $roles = Role::where('name','!=','Warga')->get();
        return view('users.form',compact('action','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|unique:users',
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string',
            'role'      => 'required',
        ],[
            'username.required' => 'Username wajib diisi',
            'username.string'   => 'Username tidak valid',
            'username.unique'   => 'Username sudah dipakai',
            'name.required'     => 'Name wajib diisi',
            'name.string'       => 'Name tidak valid',
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Email tidak valid',
            'email.unique'      => 'Email sudah dipakai',
            'password.required' => 'Password wajib diisi',
            'password.string'   => 'Password tidak valid',
            'role.required'     => 'Role wajib diisi',
        ]);

        try {
            DB::beginTransaction();
            $data['users']  = $request->only(['name','username','email']);
            $data['users']['password'] = Hash::make($request->password);
            $data['users']['email_verified_at'] = \Carbon\Carbon::now();
            $users = User::create($data['users']);
            $users->assignRole($request->role);
            DB::commit();
            return redirect()->route('users.index')->with('success','Berhasil menambahkan akun pengguna');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $action = route('users.update',$user->id);
        $roles = Role::where('name','!=','Warga')->get();
        return view('users.form',compact('action','roles','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'username'  => 'required|string',
            'name'      => 'required|string',
            'email'     => 'required|email',
            'role'      => 'required',
        ];

        if($request->username != $user->username){
            $rules = [
                'username'  => 'required|string|unique:users',
            ];
        }

        if($request->email != $user->email){
            $rules = [
                'email'     => 'required|email|unique:users',
            ];
        }

        $request->validate($rules,[
            'username.required' => 'Username wajib diisi',
            'username.string'   => 'Username tidak valid',
            'username.unique'   => 'Username sudah dipakai',
            'name.required'     => 'Name wajib diisi',
            'name.string'       => 'Name tidak valid',
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Email tidak valid',
            'email.unique'      => 'Email sudah dipakai',
            'role.required'     => 'Role wajib diisi',
        ]);

        $nama = $user->name;
        try {
            DB::beginTransaction();
            $data['users']  = $request->only(['name','username','email']);
            if(!is_null($request->password)){
                $data['users']['password'] = Hash::make($request->password);
            }
            $data['users']['email_verified_at'] = \Carbon\Carbon::now();
            User::where('id',$user->id)->update($data['users']);
            if(!$user->hasRole($request->role)){
                $user->assignRole($request->role);
            }

            DB::commit();
            return redirect()->route('users.index')->with('success','Berhasil merubah akun pengguna '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $nama = $user->name;
        try {
            DB::beginTransaction();
            User::where('id',$user->id)->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success','Berhasil menghapus akun pengguna '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

     /**
     * Banned the specified resource from storage.
     */
    public function banned(User $users)
    {
        $nama = $users->name;
        try {
            DB::beginTransaction();
            User::where('id',$users->id)->update([
                'banned'    => true,
            ]);
            DB::commit();
            return redirect()->route('users.index')->with('success','Berhasil menonaktifkan akun pengguna '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Unbanned the specified resource from storage.
     */
    public function unbanned(User $users)
    {
        $nama = $users->name;
        try {
            DB::beginTransaction();
            User::where('id',$users->id)->update([
                'banned'    => false,
            ]);
            DB::commit();
            return redirect()->route('users.index')->with('success','Berhasil mengaktifkan kembali akun pengguna '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
