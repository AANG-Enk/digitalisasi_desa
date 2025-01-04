<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_berita = Berita::with('pembuat','kategori')->whereNull('deleted_at')->orderBy('published_at','DESC')->get();
        return view('berita.index',compact('list_berita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('berita.store');
        $list_kategori_berita = KategoriBerita::whereNull('deleted_at')->get();
        return view('berita.form',compact('action','list_kategori_berita'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'kategori_berita_id'    => 'required',
            'pin'                   => 'required'
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.required'        => 'Thumbnail berita harus disi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
            'pin.required'          => 'Pin harus dipilih',
            'kategori_berita_id.required'   => 'Kategori Berita harus harus dipilih',
        ]);

        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('berita-thumbnail','public');
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            Berita::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('berita.index')->with('success','Berhasil menambahkan berita '.$request->judul);
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
        $berita = Berita::where('slug',$slug)->firstOrFail();
        return view('berita.detail',compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $beritum)
    {
        $action = route('berita.update',$beritum->id);
        $list_kategori_berita = KategoriBerita::whereNull('deleted_at')->get();
        return view('berita.form',compact('action','beritum','list_kategori_berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $beritum)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
            'kategori_berita_id'    => 'required',
            'pin'                   => 'required'
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            // 'image.required'        => 'Thumbnail berita harus disi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
            'pin.required'          => 'Pin harus dipilih',
            'kategori_berita_id.required'   => 'Kategori Berita harus harus dipilih',
        ]);

        $judul = $beritum->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($beritum->image) {
                    Storage::delete($beritum->image);
                }
                $request['image']    = $request->file('thumb')->store('berita-thumbnail','public');
            }
            if ($request->judul != $beritum->judul) {
                $request['slug'] = SlugService::createSlug(Berita::class, 'slug', $request->judul);
            }
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            Berita::where('id',$beritum->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('berita.index')->with('success','Berhasil merubah berita '.$judul);
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
    public function destroy(Berita $beritum)
    {
        $judul = $beritum->judul;
        try {
            DB::beginTransaction();
            Berita::where('id',$beritum->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('berita.index')->with('success','Berhasil menghapus berita '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
