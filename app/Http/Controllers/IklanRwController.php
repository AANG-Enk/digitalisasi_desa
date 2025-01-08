<?php

namespace App\Http\Controllers;

use App\Models\IklanRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class IklanRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_iklan = IklanRw::whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        return view('iklan.index',compact('list_iklan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('adsrw.store');
        return view('iklan.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'start'                 => 'required',
            'end'                   => 'required',
            'thumb'                 => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'start.required'        => 'Periode mulai ditayangkan harus diisi',
            'end.required'          => 'Periode berakhir ditayangkan harus diisi',
            'thumb.required'        => 'Thumbnail berita harus disi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
        ]);

        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('iklan-rw-thumbnail','public');
            $request['start']           = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start'])->format('Y-m-d');
            $request['end']             = \Carbon\Carbon::createFromFormat('d-m-Y',$request['end'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            IklanRw::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('adsrw.index')->with('success','Berhasil menambahkan adds rw '.$request->judul);
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
    public function show(IklanRw $adsrw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IklanRw $adsrw)
    {
        $action = route('adsrw.update',$adsrw->id);
        return view('iklan.form',compact('action','adsrw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IklanRw $adsrw)
    {
        $request->validate([
            'judul'                 => 'required',
            'start'                 => 'required',
            'end'                   => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:2048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'start.required'        => 'Periode mulai ditayangkan harus diisi',
            'end.required'          => 'Periode berakhir ditayangkan harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 2mb',
        ]);

        $judul = $adsrw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($adsrw->image) {
                    Storage::delete($adsrw->image);
                }
                $request['image']    = $request->file('thumb')->store('iklan-rw-thumbnail','public');
            }
            if ($request->judul != $adsrw->judul) {
                $request['slug'] = SlugService::createSlug(IklanRw::class, 'slug', $request->judul);
            }
            $request['start']           = \Carbon\Carbon::createFromFormat('d-m-Y',$request['start'])->format('Y-m-d');
            $request['end']             = \Carbon\Carbon::createFromFormat('d-m-Y',$request['end'])->format('Y-m-d');
            IklanRw::where('id',$adsrw->id)->update($request->except(['_token','thumb','_method']));
            DB::commit();
            return redirect()->route('adsrw.index')->with('success','Berhasil merubah adds rw '.$judul);
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
    public function destroy(IklanRw $adsrw)
    {
        $judul = $adsrw->judul;
        try {
            DB::beginTransaction();
            IklanRw::where('id',$adsrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('adsrw.index')->with('success','Berhasil menghapus adds rw '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
