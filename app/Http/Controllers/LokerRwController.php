<?php

namespace App\Http\Controllers;

use App\Models\LokerRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class LokerRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('Warga')){
            $list_loker = LokerRw::whereNull('deleted_at')->paginate(9);
            return view('loker.warga',compact('list_loker'));
        }else{
            $list_loker = LokerRw::whereNull('deleted_at')->get();
            return view('loker.index',compact('list_loker'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('lokerrw.store');
        return view('loker.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'hubungi'               => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
            'posisi'                => 'required',
            'perusahaan'            => 'required',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'hubungi'               => 'Hubungi harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
            'posisi.required'       => 'Posisi harus dipilih',
            'perusahaan.required'   => 'Tempat Lowongan Kerja harus diisi'
        ]);
        try {
            DB::beginTransaction();
            if($request->file('thumb')){
                $request['image']           = $request->file('thumb')->store('loker-thumbnail','public');
            }
            $request['user_id']         = auth()->user()->id;
            LokerRw::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('lokerrw.index')->with('success','Berhasil menambahkan Lowongan Kerja '.$request->judul);
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
    public function show($slug)
    {
        $lokerrw = LokerRw::where('slug',$slug)->firstOrFail();
        return view('loker.detail',compact('lokerrw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LokerRw $lokerrw)
    {
        $action = route('lokerrw.update',$lokerrw->id);
        return view('loker.form',compact('action','lokerrw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LokerRw $lokerrw)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'hubungi'               => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
            'posisi'                => 'required',
            'perusahaan'            => 'required',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'hubungi'               => 'Hubungi harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
            'posisi.required'       => 'Posisi harus dipilih',
            'perusahaan.required'   => 'Tempat Lowongan Kerja harus diisi'
        ]);

        $judul = $lokerrw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($lokerrw->image) {
                    Storage::delete($lokerrw->image);
                }
                $request['image']    = $request->file('thumb')->store('loker-thumbnail','public');
            }
            if ($request->judul != $lokerrw->judul) {
                $request['slug'] = SlugService::createSlug(LokerRw::class, 'slug', $request->judul);
            }

            LokerRw::where('id',$lokerrw->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('lokerrw.index')->with('success','Berhasil merubah Lowongan Kerja '.$judul);
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
    public function destroy(LokerRw $lokerrw)
    {
        $judul = $lokerrw->judul;
        try {
            DB::beginTransaction();
            LokerRw::where('id',$lokerrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('lokerrw.index')->with('success','Berhasil Menghapus Lowongan Kerja '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
