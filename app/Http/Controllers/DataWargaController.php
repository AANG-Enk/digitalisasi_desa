<?php

namespace App\Http\Controllers;

use App\Models\ExcelImportProgres;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use App\Imports\DataWargaMultipleSheetImport;
use App\Imports\DataWargaImport;
use Maatwebsite\Excel\Facades\Excel;

class DataWargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wargas = User::whereHas('roles',function($q){
            $q->where('name','Warga');
        })->orderBy('rt','ASC')->get();
        return view('warga.index',compact('wargas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('datawarga.store');
        return view('warga.form',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kk'    =>  'required|numeric|digits:16',
            'nik'   =>  'required|numeric|digits:16|unique:users',
            'name'  =>  'required',
        ],[
            'kk.required'   => 'No. KK harus diisi',
            'kk.numeric'    => 'No. KK harus angka',
            'kk.digits'     => 'No. KK harus 16 angka',
            'nik.required'  => 'No. NIK harus diisi',
            'nik.numeric'   => 'No. NIK harus angka',
            'nik.digits'    => 'No. NIK harus 16 angka',
            'nik.unique'    => 'No. NIK sudah digunakan',
            'name.required' => 'Nama harus diisi',
        ]);

        try {
            DB::beginTransaction();
            $data = array_map('strtoupper', $request->all());
            $data['tanggal_lahir']   = \Carbon\Carbon::createFromFormat('d-m-Y',$request['tanggal_lahir'])->format('Y-m-d');
            $data['username']        = $request['nik'];
            $data['email']           = $request['nik'].'@gmail.com';
            $data['password']        = Hash::make($request['nik']);
            $data['email_verified_at']   = \Carbon\Carbon::now();
            $user = User::create($data);
            $user->assignRole('Warga');
            DB::commit();
            return redirect()->route('datawarga.index')->with('success','Berhasil menambahkan data warga '.$request->name);
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $datawarga)
    {
        $action = route('datawarga.update',$datawarga->id);
        $user = $datawarga;
        return view('warga.form',compact('action','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $datawarga)
    {
        $rules = [
            'kk'    =>  'required|numeric|digits:16',
            'nik'   =>  'required|numeric|digits:16',
            'name'  =>  'required',
        ];
        if(!is_null($request->nik) && $request->nik != $datawarga->nik){
            $rules['nik']   = 'required|numeric|digits:16|unique:users';
        }
        $request->validate($rules,[
            'kk.required'   => 'No. KK harus diisi',
            'kk.numeric'    => 'No. KK harus angka',
            'kk.digits'     => 'No. KK harus 16 angka',
            'nik.required'  => 'No. NIK harus diisi',
            'nik.numeric'   => 'No. NIK harus angka',
            'nik.digits'    => 'No. NIK harus 16 angka',
            'nik.unique'    => 'No. NIK sudah digunakan',
            'name.required' => 'Nama harus diisi',
        ]);

        $name = $datawarga->name;
        try {
            DB::beginTransaction();
            $request['tanggal_lahir']   = \Carbon\Carbon::createFromFormat('d-m-Y',$request['tanggal_lahir'])->format('Y-m-d');
            User::where('id',$datawarga->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('datawarga.index')->with('success','Berhasil merubah data warga '.$name);
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
    public function destroy(User $datawarga)
    {
        $name = $datawarga->name;
        try {
            DB::beginTransaction();
            User::where('id',$datawarga->id)->delete();
            DB::commit();
            return redirect()->route('datawarga.index')->with('success','Berhasil menghapus data warga '.$name);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }


    public function import_index()
    {
        $action = route('datawarga.import.store');
        return view('warga.import',compact('action'));
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'file'  => 'required|mimes:xls,xlsx|max:1024',
        ],[
            'file.required' => 'File Excel harus dimasukkan',
            'file.mimes'    => 'File Excel tidak valid',
            'file.max'      => 'File Excel Maximal 1mb',
        ]);

        ini_set('max_execution_time', '0');
		ini_set('memory_limit', '-1');

        try {
            $totalRows = count(Excel::toArray(new DataWargaImport, $request->file('file'), null, \Maatwebsite\Excel\Excel::XLSX)[0]);

            ExcelImportProgres::updateOrCreate([
                'key'   => 'DATA WARGA'
            ],[
                'total_rows' => $totalRows-2,
                'processed_rows' => 0,
            ]);
            Excel::import(new DataWargaMultipleSheetImport, $request->file('file'));

            return response()->json([
                'success'       => true,
                'status'        => 'Mohon Tunggu... Sedang Melakukan Import Data',
            ]);

            //UPLOAD FILE
            // $file = $request->file('file');
            // $jobId = uniqid('import_');
            // $filename = time() . '.' . $file->getClientOriginalExtension();
            // $file->storeAs('temp', $filename, 'public');

            // dispatch(new ImportDataWargaJob(storage_path('app/public/temp/'.$filename),$jobId));

            // Cache::put('import_total_rows', 0);
            // $total_rows = count(Excel::toArray([], $file)[0])-2;
            // Cache::put('import_total_rows', $total_rows);

            // Cache::put('import_progress', 0);
            // Excel::import(new DataWargaMultipleSheetImport, $file);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage(), $e->getTrace());
            return response()->json([
                'success'       => false,
                'status'        => 'Gagal Import, Silahkan ulangi kembali atau kontak admin.',
                'pesan'         => $e->getMessage(),
            ]);
        }
    }

    public function checkImportStatus()
    {
        $progress = ExcelImportProgres::where('key','DATA WARGA')->first();

        if (!$progress) {
            return response()->json(['message' => 'No import in progress'], 404);
        }

        return response()->json([
            'total' => $progress->total_rows,
            'processed' => $progress->processed_rows,
        ]);
    }

    public function pilih_rt(User $user)
    {
        $name = $user->name;
        try {
            DB::beginTransaction();
            User::where('rt',$user->rt)->update(['ketua_rt'  => false]);
            User::where('id',$user->id)->update([
                'ketua_rt'  => true,
            ]);
            DB::commit();
            return redirect()->route('datawarga.index')->with('success','Warga '.$name.' Menjadi Ketua RT '.$user->rt);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function pilih_rw(User $user)
    {
        $name = $user->name;
        try {
            DB::beginTransaction();
            User::where('rw',$user->rw)->update(['ketua_rw'  => false]);
            $existingUser = User::role('RW')->where('rw', $user->rw)->first();
            if ($existingUser) {
                $existingUser->removeRole('RW');
            }
            User::where('id',$user->id)->update([
                'ketua_rw'  => true,
            ]);
            $user->assignRole('RW');
            DB::commit();
            return redirect()->route('datawarga.index')->with('success','Warga '.$name.' Menjadi Ketua RW '.$user->rw);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
