<?php

namespace App\Http\Controllers;

use App\Models\LayananSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\SuratTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Pdf;

class LayananSuratController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasRole('Admin RW')){
            $list_layanan_surat = LayananSurat::with('tujuan.surat','pembuat')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            if(!is_null(auth()->user()->rw)){
                $list_layanan_surat = LayananSurat::with('tujuan.surat','pembuat')->where('rw',auth()->user()->rw)->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            }
        }elseif(auth()->user()->hasRole('Warga') && auth()->user()->ketua_rt){
            $list_layanan_surat = LayananSurat::with('tujuan.surat','pembuat')->where('rt',auth()->user()->rt)->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        }elseif(auth()->user()->hasRole('Warga') && !auth()->user()->ketua_rt){
            $list_layanan_surat = LayananSurat::with('tujuan.surat','pembuat')->where('user_id',auth()->user()->id)->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        }else{
            $list_layanan_surat = LayananSurat::with('tujuan.surat','pembuat')->whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        }
        return view('layanansurat.index',compact('list_layanan_surat'));
    }

    public function create()
    {
        $rt = User::where([['rt',auth()->user()->rt],['ketua_rt',true]])->first();
        $rw = User::where([['rw',auth()->user()->rw],['ketua_rw',true]])->first();
        if(is_null($rt)){
            return redirect()->route('layanansurat.index')->with('error','RT '.auth()->user()->rt.' Belum memilih ketua RT, harap lakukan pemilihan Ketua RT dahulu');
        }
        if(is_null($rw)){
            return redirect()->route('layanansurat.index')->with('error','RW '.auth()->user()->rw.' Belum memilih ketua RW, harap lakukan pemilihan Ketua RW dahulu');
        }

        $list_surat = Surat::whereNull('deleted_at')->get();
        $action = route('layanansurat.store');
        return view('layanansurat.form',compact('action','list_surat'));
    }

    public function tujuan(Request $request)
    {
        return SuratTujuan::where('surat_id',$request['surat'])->get()->toArray();
    }

    public function store(Request $request)
    {
        $request->validate([
            'surat_tujuan_id'   => 'required',
            'surat'             => 'required'
        ],[
            'surat.required'    => 'Surat harus dipilih',
            'surat_tujuan_id.required'  => 'Tujuan surat dibuat harus diisi',
        ]);
        try {
            DB::beginTransaction();
            $rt = User::where([['rt',auth()->user()->rt],['ketua_rt',true]])->first();
            $rw = User::where([['rw',auth()->user()->rw],['ketua_rw',true]])->first();
            $request['user_id'] = auth()->user()->id;
            $request['rt']      = auth()->user()->rt;
            $request['rw']      = auth()->user()->rw;
            $request['nama_rt'] = $rt->name;
            $request['nama_rw'] = $rw->name;
            LayananSurat::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('layanansurat.index')->with('success','Berhasil membuat surat, silahkan hubungi RT dan RW untuk menyetujui surat tersebut');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(LayananSurat $layanansurat)
    {
        $action = route('layanansurat.update',$layanansurat->id);
        return view('layanansurat.form',compact('action','layanansurat'));
    }

    public function update(Request $request,LayananSurat $layanansurat)
    {
        $request->validate([
            'surat_tujuan_id'   => 'required',
            'surat'             => 'required'
        ],[
            'surat.required'    => 'Surat harus dipilih',
            'surat_tujuan_id.required'  => 'Tujuan surat dibuat harus diisi',
        ]);
        try {
            DB::beginTransaction();
            LayananSurat::where('id',$layanansurat->id)->update($request->except(['_token',"_method"]));
            DB::commit();
            return redirect()->route('layanansurat.index')->with('success','Berhasil merubah surat, silahkan hubungi RT dan RW untuk menyetujui surat tersebut');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(LayananSurat $layanansurat)
    {
        try {
            DB::beginTransaction();
            LayananSurat::where('id',$layanansurat->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('layanansurat.index')->with('success','Berhasil menghapus surat, silahkan hubungi RT dan RW untuk menyetujui surat tersebut');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function nomorrt_index(LayananSurat $layanansurat)
    {
        $flag = 'rt';
        $action = route('layanansurat.nomorrt.store',$layanansurat->id);
        return view('layanansurat.nomor',compact('action','layanansurat','flag'));
    }

    public function nomorrt_store(Request $request,LayananSurat $layanansurat)
    {
        $request->validate([
            'nomor'  => 'required'
        ],[
            'nomor.required' => 'Nomor Registrasi RT Wajib diisi'
        ]);
        try {
            DB::beginTransaction();
            LayananSurat::where('id',$layanansurat->id)->update([
                'nomor_surat_rt'             => $request['nomor'],
                'tanggal_tanda_tangan'  => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('layanansurat.index')->with('success','Berhasil memberikan nomor registrasi rt');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function nomorrw_index(LayananSurat $layanansurat)
    {
        $flag = 'rw';
        $action = route('layanansurat.nomorrw.store',$layanansurat->id);
        return view('layanansurat.nomor',compact('action','layanansurat','flag'));
    }

    public function nomorrw_store(Request $request,LayananSurat $layanansurat)
    {
        $request->validate([
            'nomor'  => 'required'
        ],[
            'nomor.required' => 'Nomor Registrasi RT Wajib diisi'
        ]);
        try {
            DB::beginTransaction();
            LayananSurat::where('id',$layanansurat->id)->update([
                'nomor_surat_rw'    => $request['nomor'],
            ]);
            DB::commit();
            return redirect()->route('layanansurat.index')->with('success','Berhasil memberikan nomor registrasi rw');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function print(LayananSurat $layanansurat)
    {
        try {
            ob_start();
            $this->fpdf = new Pdf($layanansurat);
            $this->fpdf->AddPage("P", [330,215]);

            $titik_awal_x = 19;
            $titik_awal_y = 5;

            if($layanansurat->pembuat->status_pernikahan == "B"){
                $status_kawin = "Tidak Kawin";
            }elseif($layanansurat->pembuat->status_pernikahan == "P"){
                if($layanansurat->pembuat->jenis_kelamin == "L"){
                    $status_kawin = "Duda";
                }else{
                    $status_kawin = "Janda";
                }
            }else{
                $status_kawin = "Kawin";
            }

            $jenis_kelamin = "Laki-Laki";
            if($layanansurat->pembuat->jenis_kelamin == "P"){
                $jenis_kelamin = "Perempuan";
            }

            $this->fpdf->SetFont('Times', 'B', 14);
            $this->fpdf->SetX((20));
            $this->fpdf->MultiCell(180, 5, $layanansurat->tujuan->surat->nama, 0, 'C', FALSE);
            $this->fpdf->SetX((83));
            $this->fpdf->MultiCell(53, 0.2, "", 1, 'C', TRUE);
            $this->fpdf->Ln(4);
            $this->fpdf->SetFont('Times', '', 12);
            $this->fpdf->SetX((20));
            $this->fpdf->MultiCell(180, 5, "NO : ".$layanansurat->nomor_surat_rt, 0, 'C', FALSE);
            $this->fpdf->Ln(4);
            $this->fpdf->SetX((15));
            $this->fpdf->MultiCell(180, 5, "Ketua Rukun Tetangga ".$layanansurat->rt." Rukun Warga ".$layanansurat->rw." Kelurahan Babakan Kecamatan Babakan Ciparay Kota Bandung, dengan ini menerangkan bahwa :", 0, 'J', FALSE);
            $this->fpdf->Ln(4);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->name, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Nama", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->tempat_lahir.",".\Carbon\Carbon::parse($layanansurat->pembuat->tanggal_lahir)->isoFormat('DD-MMMM-YYYY'), 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Tempat,Tgl.Lahir", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->nik, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "No. NIK", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->kk, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "No. KK", 0, 'L', FALSE);
            $currentY = $this->fpdf->GetY();
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$jenis_kelamin, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Jenis Kelamin", 0, 'L', FALSE);
            $this->fpdf->SetY(($currentY));
            $this->fpdf->SetX((170));
            $this->fpdf->Cell(140, 7, ": ", 0, 0, 'L', FALSE);
            $this->fpdf->SetX((130));
            $this->fpdf->MultiCell(35, 7, "Gol Darah", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$status_kawin, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Status Perkawinan", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->jenis_pekerjaan, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Pekerjaan", 0, 'L', FALSE);
            $this->fpdf->SetX((90));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->pembuat->alamat, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((50));
            $this->fpdf->MultiCell(35, 7, "Alamat", 0, 'L', FALSE);
            $this->fpdf->Ln(5);
            $this->fpdf->SetX((15));
            $this->fpdf->MultiCell(180, 5, "Orang tersebut diatas benar-benar Warga Tetap/Sementara di daerah kami dan bermaksud untuk membuat ".$layanansurat->tujuan->nama, 0, 'J', FALSE);
            $this->fpdf->Ln(5);
            $this->fpdf->SetX((15));
            $this->fpdf->MultiCell(180, 5, "Demikian pengantar ini dibuat dengan sebenarnya dan untuk dipergunakan sebagaimana mestinya.", 0, 'J', FALSE);
            $this->fpdf->Ln(5);
            $this->fpdf->SetX((20));
            $this->fpdf->Cell(150, 7, ": ".$layanansurat->nomor_surat_rw, 0, 0, 'L', FALSE);
            $this->fpdf->SetX((15));
            $this->fpdf->MultiCell(30, 7, "No", 0, 'L', FALSE);

            $currentY = $this->fpdf->GetY();
            $this->fpdf->SetFont('Times', '', 12);
            $this->fpdf->SetY(($currentY+5));
            $this->fpdf->SetX((20));
            $this->fpdf->MultiCell(60, 5, "Mengetahui", 0, 'C', FALSE);

            $this->fpdf->SetY(($currentY+5));
            $this->fpdf->SetX((140));
            $this->fpdf->MultiCell(60, 5, "Bandung, ".\Carbon\Carbon::parse($layanansurat->tanggal_tanda_tangan)->isoFormat('DD-MMMM-YYYY'), 0, 'C', FALSE);

            $this->fpdf->SetY(($currentY+10));
            $this->fpdf->SetX((20));
            $this->fpdf->MultiCell(60, 5, "Ketua Rukun Warga ".$layanansurat->rw, 0, 'C', FALSE);

            $this->fpdf->SetY(($currentY+10));
            $this->fpdf->SetX((140));
            $this->fpdf->MultiCell(60, 5, "Ketua Rukun Tetangga ".$layanansurat->rt, 0, 'C', FALSE);

            $this->fpdf->SetY(($currentY+30));
            $this->fpdf->SetX((20));
            $this->fpdf->MultiCell(60, 5, "( ".$layanansurat->nama_rw." )", 0, 'C', FALSE);

            $this->fpdf->SetY(($currentY+30));
            $this->fpdf->SetX((140));
            $this->fpdf->MultiCell(60, 5, "( ".$layanansurat->nama_rt." )", 0, 'C', FALSE);


            $x = 1;
            $image_x = $titik_awal_x;
            $image_y = $titik_awal_y;

            $this->fpdf->Output('D',$layanansurat->tujuan->nama.' Untuk '.$layanansurat->pembuat->name.' .pdf');
            ob_end_flush();
            exit;
        } catch (\Throwable $th) {
            dd($th->getTraceAsString());
        }

    }
}
