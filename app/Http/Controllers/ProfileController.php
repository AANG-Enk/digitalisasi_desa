<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $action = route('profile.store');
        return view('profile.index',compact('user','action'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username'          => 'required|string',
            'name'              => 'required|string',
            'tempat_lahir'      => 'required|string',
            'alamat'            => 'required|string',
            'jenis_pekerjaan'   => 'required|string',
            'kk'                => 'required|numeric|digits:16',
            'nik'               => 'required|numeric|digits:16',
            'rt'                => 'required|numeric',
            'rw'                => 'required|numeric',
            'hp'                => 'required|numeric',
            'email'             => 'required|email',
            'tanggal_lahir'     => 'required',
            'jenis_kelamin'     => 'required',
            'status_pernikahan' => 'required',
        ];

        $message = [
            'username.required'                 => 'Username harus diisi',
            'username.string'                   => 'Username harus string',
            'name.required'                     => 'Nama Lengkap harus diisi',
            'name.string'                       => 'Nama Lengkap harus string',
            'tempat_lahir.required'             => 'Tempat Lahir harus diisi',
            'tempat_lahir.string'               => 'Tempat Lahir harus string',
            'jenis_pekerjaan.required'          => 'Jenis Pekerjaan harus diisi',
            'jenis_pekerjaan.string'            => 'Jenis Pekerjaan harus string',
            'alamat.required'                   => 'Alamat Rumah harus diisi',
            'alamat.string'                     => 'Alamat Rumah harus string',
            'email.required'                    => 'E-Mail harus diisi',
            'email.email'                       => 'E-Mail tidka valid',
            'kk.required'                       => 'No. KK harus diisi',
            'kk.numeric'                        => 'No. KK harus angka',
            'kk.digits'                         => 'No. KK harus 16 angka',
            'nik.required'                      => 'No. NIK harus diisi',
            'nik.numeric'                       => 'No. NIK harus angka',
            'nik.digits'                        => 'No. NIK harus 16 angka',
            'rt.required'                       => 'No. RT harus diisi',
            'rt.numeric'                        => 'No. RT harus angka',
            'rw.required'                       => 'No. RW harus diisi',
            'rw.numeric'                        => 'No. RW harus angka',
            'hp.required'                       => 'No. Whatsapp harus diisi',
            'hp.numeric'                        => 'No. Whatsapp harus angka',
            'tanggal_lahir.required'            => 'Tanggal Lahir harus diisi',
            'jenis_kelamin.required'            => 'Jenis Kelamin harus dipilih',
            'status_pernikahan.required'        => 'Status Pernikahan harus dipilih'
        ];

        if($request->nik != $user->nik){
            $rules['nik']           = 'required|string|unique:users';
            $message['nik.unique']  = 'Username sudah terpakai';
        }

        if($request->email != $user->email){
            $rules['email']           = 'required|string|unique:users';
            $message['email.unique']  = 'Email sudah terpakai';
        }

        $request->validate($rules,$message);

        try {
            DB::beginTransaction();
            $request['tanggal_lahir']   = \Carbon\Carbon::createFromFormat('d-m-Y',$request['tanggal_lahir'])->format('Y-m-d');
            User::where('id',$user->id)->update($request->except(['_token']));
            DB::commit();
            return redirect()->route('profile.index')->with('success','Berhasil merubah profile');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
