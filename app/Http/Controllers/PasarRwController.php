<?php

namespace App\Http\Controllers;

use App\Models\PasarRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class PasarRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('Warga')){
            $list_pasar = PasarRw::whereNull('deleted_at')->orderBy('created_at','DESC')->paginate(9);
            return view('pasar.warga',compact('list_pasar'));
        }else{
            $list_pasar = PasarRw::whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            return view('pasar.index',compact('list_pasar'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('pasarrw.store');
        return view('pasar.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'thumb'                 => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'hubungi'               => 'required|numeric',
            'harga'                 => 'required|numeric'
        ],[
            'judul.required'        => 'Nama Produk harus diisi',
            'deskripsi.required'    => 'Deskripsi Produk harus diisi',
            'thumb.required'        => 'Thumbnail Produk harus disi',
            'thumb.image'           => 'Thumbnail Produk harus gambar',
            'thumb.mimes'           => 'Thumbnail Produk tidak valid',
            'thumb.max'             => 'Thumbnail Produk maksimal 2mb',
            'hubungi.required'      => 'No. Whatsapp Penjual harus diisi',
            'hubungi.numeric'       => 'No. Whatsapp Penjual harus diisi dengan angka',
            'harga.required'        => 'Harga Produk harus diisi',
            'harga.numeric'         => 'Harga Produk harus diisi dengan angka'
        ]);
        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('pasar-rw-thumbnail','public');
            $request['user_id']         = auth()->user()->id;
            PasarRw::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('pasarrw.index')->with('success','Berhasil menambahkan product di pasar rw '.$request->judul);
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
        $pasarrw = PasarRW::where('slug',$slug)->firstOrFail();
        return view('pasar.detail',compact('pasarrw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PasarRw $pasarrw)
    {
        $action = route('pasarrw.update',$pasarrw->id);
        return view('pasar.form',compact('action','pasarrw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PasarRw $pasarrw)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
            'hubungi'               => 'required|numeric',
            'harga'                 => 'required|numeric'
        ],[
            'judul.required'        => 'Nama Produk harus diisi',
            'deskripsi.required'    => 'Deskripsi Produk harus diisi',
            // 'thumb.required'        => 'Thumbnail Produk harus disi',
            'thumb.image'           => 'Thumbnail Produk harus gambar',
            'thumb.mimes'           => 'Thumbnail Produk tidak valid',
            'thumb.max'             => 'Thumbnail Produk maksimal 2mb',
            'hubungi.required'      => 'No. Whatsapp Penjual harus diisi',
            'hubungi.numeric'       => 'No. Whatsapp Penjual harus diisi dengan angka',
            'harga.required'        => 'Harga Produk harus diisi',
            'harga.numeric'         => 'Harga Produk harus diisi dengan angka'
        ]);

        $judul = $pasarrw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($pasarrw->image) {
                    Storage::delete($pasarrw->image);
                }
                $request['image']    = $request->file('thumb')->store('pasar-rw-thumbnail','public');
            }
            if ($request->judul != $pasarrw->judul) {
                $request['slug'] = SlugService::createSlug(PasarRw::class, 'slug', $request->judul);
            }
            PasarRw::where('id',$pasarrw->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('pasarrw.index')->with('success','Berhasil merubah produk pasar rw '.$judul);
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
    public function destroy(PasarRw $pasarrw)
    {
        $judul = $pasarrw->judul;
        try {
            DB::beginTransaction();
            PasarRw::where('id',$pasarrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('pasarrw.index')->with('success','Berhasil menghapus produk pasar rw '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
