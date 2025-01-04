<?php

namespace App\Http\Controllers;

use App\Models\LaporRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class LaporRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('Warga')){
            $list_lapor_rw = LaporRw::with('pelapor')->where('user_id',auth()->user()->id)->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        }else{
            $list_lapor_rw = LaporRw::with('pelapor')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        }
        return view('laporrw.index',compact('list_lapor_rw'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('laporrw.store');
        return view('laporrw.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
        ],[
            'judul.required'    => 'Judul Informasi harus diisi',
            'deskripsi.required'    => 'Keterangan Informasi harus diisi',
        ]);
        try {
            DB::beginTransaction();
            $request['user_id'] = auth()->user()->id;
            LaporRw::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('laporrw.index')->with('success','Berhasil menambahkan laporan '.$request->judul);
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
    public function show(LaporRw $laporrw)
    {
        return view('laporrw.detail',compact('laporrw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporRw $laporrw)
    {
        $action = route('laporrw.update',$laporrw->id);
        return view('laporrw.form',compact('action','laporrw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporRw $laporrw)
    {
        $request->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
        ],[
            'judul.required'    => 'Judul Informasi harus diisi',
            'deskripsi.required'    => 'Keterangan Informasi harus diisi',
        ]);

        $judul = $laporrw->judul;
        try {
            DB::beginTransaction();
            if($request->judul != $laporrw->judul){
                $request['slug'] = SlugService::createSlug(LaporRw::class, 'slug', $request->judul);
            }
            LaporRw::where('id',$laporrw->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('laporrw.index')->with('success','Berhasil merubah laporan '.$judul);
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
    public function destroy(LaporRw $laporrw)
    {
        $judul = $laporrw->judul;
        try {
            DB::beginTransaction();
            LaporRw::where('id',$laporrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('laporrw.index')->with('success','Berhasil menghapus laporan '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function dibaca(LaporRw $laporrw)
    {
        $judul = $laporrw->judul;
        try {
            DB::beginTransaction();
            LaporRw::where('id',$laporrw->id)->update([
                'is_read'    => true,
            ]);
            DB::commit();
            return redirect()->route('laporrw.index')->with('success','Berhasil membaca laporan '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
