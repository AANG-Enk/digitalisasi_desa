<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\BayarDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class DonasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('Warga')){
            $list_donasi = Donasi::with('pembuat')->withSum('bayars', 'nominal')->whereNull('deleted_at')->orderBy('created_at','DESC')->paginate(9);
            return view('donasi.warga',compact('list_donasi'));
        }else{
            $list_donasi = Donasi::with('pembuat')->withSum(['bayars' => function($q){
                $q->where('is_verified',true);
            }], 'nominal')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            $list_bayar = BayarDonasi::with('donasi','bayar')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            return view('donasi.index',compact('list_donasi','list_bayar'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('ireda.store');
        return view('donasi.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'alamat'                => 'required',
            'berakhir'              => 'required',
            'target'                => 'required|numeric',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'alamat'                => 'Alamat harus diisi',
            'berakhir.required'     => 'Akhir Periode harus diisi',
            'target.required'       => 'Target Donasi harus diisi',
        ]);
        try {
            DB::beginTransaction();
            $request['berakhir']        = \Carbon\Carbon::createFromFormat('d-m-Y',$request['berakhir'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            Donasi::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('ireda.index')->with('success','Berhasil menambahkan donasi '.$request->judul);
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
    public function show(Donasi $ireda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donasi $ireda)
    {
        $action = route('ireda.update',$ireda->id);
        return view('donasi.form',compact('action','ireda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donasi $ireda)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'alamat'                => 'required',
            'berakhir'              => 'required',
            'target'                => 'required|numeric',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'alamat'                => 'Alamat harus diisi',
            'berakhir.required'     => 'Akhir Periode harus diisi',
            'target.required'       => 'Target Donasi harus diisi',
        ]);

        $judul = $ireda->judul;
        try {
            DB::beginTransaction();
            $request['berakhir']        = \Carbon\Carbon::createFromFormat('d-m-Y',$request['berakhir'])->format('Y-m-d');
            Donasi::where('id',$ireda->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('ireda.index')->with('success','Berhasil menambahkan donasi '.$judul);
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
    public function destroy(Donasi $ireda)
    {
        $judul = $ireda->judul;
        try {
            DB::beginTransaction();
            Donasi::where('id',$ireda->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('ireda.index')->with('success','Berhasil menghapus donasi '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
