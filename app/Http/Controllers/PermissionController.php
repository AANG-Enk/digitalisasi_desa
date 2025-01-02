<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('permissions.store');
        return view('permission.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|unique:permissions'
        ],[
            'name.required' => 'Nama Permission wajib diisi',
            'name.string'   => 'Nama Permission tidak valid',
            'name.unique'   => 'Nama Permission sudah dipakai',
        ]);

        try {
            DB::beginTransaction();
            Permission::create($request->only(['name']));
            DB::commit();
            return redirect()->route('permissions.index')->with('success','Berhasil menambahkan permission');
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
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $action = route('permissions.update',$permission->id);
        return view('permission.form',compact('action','permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'  => 'required|string|unique:permissions'
        ],[
            'name.required' => 'Nama Permission wajib diisi',
            'name.string'   => 'Nama Permission tidak valid',
            'name.unique'   => 'Nama Permission sudah dipakai',
        ]);

        $name = $permission->name;
        try {
            DB::beginTransaction();
            Permission::where('id',$permission->id)->update($request->only(['name']));
            DB::commit();
            return redirect()->route('permissions.index')->with('success','Berhasil merubah permission '.$name);
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
    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        try {
            DB::beginTransaction();
            Permission::where('id',$permission->id)->delete();
            DB::commit();
            return redirect()->route('permissions.index')->with('success','Berhasil menghapus permission '.$name);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
