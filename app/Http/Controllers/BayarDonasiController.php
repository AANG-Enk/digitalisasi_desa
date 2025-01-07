<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\BayarDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BayarDonasiController extends Controller
{
    public function index(Donasi $donasi)
    {
        $list_bayar = BayarDonasi::where([['user_id',auth()->user()->id],['donasi_id',$donasi->id]])->orderBy('created_at','DESC')->get();
        return view('donasi.daftar_bayar',compact('donasi','list_bayar'));
    }

    public function create(Donasi $donasi)
    {
        $action = route('ireda.iuran.store',$donasi->slug);
        return view('donasi.bayar',compact('donasi','action'));
    }

    public function store(Request $request, Donasi $donasi)
    {
        $request->validate([
            'nominal'       => 'required|numeric',
            'pembayaran'    => 'required',
            'thumb'         => 'image|mimes:png,jpg,jpeg|max:2048',
        ],[
            'nominal.required'  => 'Nominal harus diisi',
            'nominal.numeric'   => 'Nominal harus diisi angka',
            'pembayaran.required'   => 'Metode Pembayaran harus dipilih',
            'thumb.image'       => 'Bukti Pembayaran harus gambar',
            'thumb.mimes'       => 'Bukti Pembayaran tidak valid',
            'thumb.max'         => 'Bukti Pembayaran maximal 2mb',
        ]);
        try {
            DB::beginTransaction();
            if($request->file('thumb')){
                $request['file']    = $request->file('thumb')->store('bukti-pembayaran','public');
            }
            $request['donasi_id']       = $donasi->id;
            $request['user_id']         = auth()->user()->id;
            BayarDonasi::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('ireda.iuran.index',$donasi->slug)->with('success','Berhasil berdonasi '.$donasi->judul.' silahkan menunggu verifikasi admin');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function bukti_index(Donasi $donasi, BayarDonasi $bayardonasi)
    {
        $action = route('ireda.iuran.bukti.store',[$donasi->slug, $bayardonasi->id]);
        return view('donasi.bukti',compact('action','donasi'));
    }

    public function bukti_store(Request $request, Donasi $donasi, BayarDonasi $bayardonasi)
    {
        $request->validate([
            'thumb'         => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ],[
            'thumb.required'    => 'Bukti Pembayaran harus diisi',
            'thumb.image'       => 'Bukti Pembayaran harus gambar',
            'thumb.mimes'       => 'Bukti Pembayaran tidak valid',
            'thumb.max'         => 'Bukti Pembayaran maximal 2mb',
        ]);
        try {
            DB::beginTransaction();
            $request['file']    = $request->file('thumb')->store('bukti-pembayaran','public');
            BayarDonasi::where('id',$bayardonasi->id)->update($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('ireda.iuran.index',$donasi->slug)->with('success','Berhasil upload bukti berdonasi '.$donasi->judul.' silahkan menunggu verifikasi admin');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function verifikasi(Request $request, Donasi $donasi, BayarDonasi $bayardonasi)
    {
        try {
            DB::beginTransaction();
            BayarDonasi::where('id',$bayardonasi->id)->update([
                'is_verified'    => true,
            ]);
            DB::commit();
            return redirect()->route('ireda.index')->with('success','Berhasil menyetujui donasi '.$bayardonasi->bayar->name.' untuk '.$donasi->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
