<?php

namespace App\Http\Controllers;

use App\Models\TaniRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class TaniRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_tani = TaniRw::with('pembuat')->whereNull('deleted_at')->orderBy('published_at','DESC')->get();
        return view('tanirw.index',compact('list_tani'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('tanirw.store');
        return view('tanirw.form',compact('action'));
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
        ]);

        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('tani-rw-thumbnail','public');
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            TaniRw::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('tanirw.index')->with('success','Berhasil menambahkan hasil tani '.$request->judul);
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
        $tanirw = TaniRw::where('slug',$slug)->firstOrFail();
        return view('tanirw.detail',compact('tanirw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaniRw $tanirw)
    {
        $action = route('tanirw.update',$tanirw->id);
        return view('tanirw.form',compact('action','tanirw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaniRw $tanirw)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
            'pin'                   => 'required'
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
            'pin.required'          => 'Pin harus dipilih',
        ]);

        $judul = $tanirw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($tanirw->image) {
                    Storage::delete($tanirw->image);
                }
                $request['image']    = $request->file('thumb')->store('berita-thumbnail','public');
            }
            if ($request->judul != $tanirw->judul) {
                $request['slug'] = SlugService::createSlug(TaniRw::class, 'slug', $request->judul);
            }
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            TaniRw::where('id',$tanirw->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('tanirw.index')->with('success','Berhasil merubah hasil tani '.$judul);
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
    public function destroy(TaniRw $tanirw)
    {
        $judul = $tanirw->judul;
        try {
            DB::beginTransaction();
            TaniRw::where('id',$tanirw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('tanirw.index')->with('success','Berhasil menghapus hasil tani '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
