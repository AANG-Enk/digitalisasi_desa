<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('roles.store');
        return view('role.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string'
        ],[
            'name.required' => 'Nama Role wajib diisi',
            'name.string'   => 'Nama Role tidak valid',
        ]);

        try {
            DB::beginTransaction();
            Role::create($request->only(['name']));
            DB::commit();
            return redirect()->route('roles.index')->with('success','Berhasil menambahkan role');
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
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $action = route('roles.update',$role->id);
        return view('role.form',compact('action','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'  => 'required|string'
        ],[
            'name.required' => 'Nama Role wajib diisi',
            'name.string'   => 'Nama Role tidak valid',
        ]);

        $nama = $role->name;
        try {
            DB::beginTransaction();
            Role::where('id',$role->id)->update($request->only(['name']));
            DB::commit();
            return redirect()->route('roles.index')->with('success','Berhasil merubah role '.$nama);
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
    public function destroy(Role $role)
    {
        $nama = $role->name;
        try {
            DB::beginTransaction();
            Role::where('id',$role->id)->delete();
            DB::commit();
            return redirect()->route('roles.index')->with('success','Berhasil menghapus role '.$nama);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
