<?php

namespace App\Http\Controllers;

use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class KategoriBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_kategori_berita = KategoriBerita::whereNull('deleted_at')->get();
        return view('kategoriberita.index',compact('list_kategori_berita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('kategoriberita.store');
        return view('kategoriberita.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required',
        ],[
            'judul.required'    => 'Judul Informasi harus diisi',
        ]);
        try {
            DB::beginTransaction();
            KategoriBerita::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('kategoriberita.index')->with('success','Berhasil menambahkan kategori berita '.$request->judul);
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
    public function show(KategoriBerita $kategoriberitum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriBerita $kategoriberitum)
    {
        $action = route('kategoriberita.update',$kategoriberitum->id);
        return view('kategoriberita.form',compact('action','kategoriberitum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriBerita $kategoriberitum)
    {
        $request->validate([
            'judul'     => 'required',
        ],[
            'judul.required'    => 'Judul Informasi harus diisi',
        ]);

        $judul = $kategoriberitum->judul;
        try {
            DB::beginTransaction();
            if($request->judul != $kategoriberitum->judul){
                $request['slug'] = SlugService::createSlug(KategoriBerita::class, 'slug', $request->judul);
            }
            KategoriBerita::where('id',$kategoriberitum->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('kategoriberita.index')->with('success','Berhasil merubah kategori berita '.$judul);
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
    public function destroy(KategoriBerita $kategoriberitum)
    {
        $judul = $kategoriberitum->judul;
        try {
            DB::beginTransaction();
            KategoriBerita::where('id',$kategoriberitum->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('kategoriberita.index')->with('success','Berhasil menghapus kategori berita '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
