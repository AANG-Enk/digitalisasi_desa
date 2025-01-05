<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use App\Models\SurveiPertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class SurveiController extends Controller
{
    public function index()
    {
        $list_survei = Survey::whereNull('deleted_at')->orderBy('created_at','DESC')->get();
        return view('surveirw.index',compact('list_survei'));
    }
    public function create()
    {
        $action = route('surveirw.store');
        return view('surveirw.form',compact('action'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required'
        ],[
            'judul.required'    => 'Judul survei harus diisi'
        ]);

        try {
            DB::beginTransaction();
            Survey::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('surveirw.index')->with('success','Berhasil menambahkan '.$request->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
    public function edit(Survey $surveirw)
    {
        $action = route('surveirw.update',$surveirw->id);
        return view('surveirw.form',compact('action','surveirw'));
    }
    public function update(Request $request, Survey $surveirw)
    {
        $request->validate([
            'judul' => 'required'
        ],[
            'judul.required'    => 'Judul survei harus diisi'
        ]);

        $judul = $surveirw->judul;
        try {
            DB::beginTransaction();
            if($request->judul != $surveirw->judul){
                $request['slug'] = SlugService::createSlug(Survey::class, 'slug', $request->judul);
            }
            Survey::where('id',$surveirw->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('surveirw.index')->with('success','Berhasil merubah '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
    public function destroy(Request $request, Survey $surveirw)
    {
        $judul = $surveirw->judul;
        try {
            DB::beginTransaction();
            Survey::where('id',$surveirw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('surveirw.index')->with('success','Berhasil menghapus '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function warga(Survey $survei)
    {
        $users = $survei->users();
        return view('surveirw.warga',compact('users','survei'));
    }

    public function warga_jawaban(Survey $survei, $nik)
    {
        $user = User::where('nik',$nik)->firstOrFail();
        $list_survei = SurveiPertanyaan::with(['jawabans' => function($q)use($user){
            $q->where('user_id',$user->id);
        }])->where('survei_id',$survei->id)->get();
        return view('surveirw.jawaban',compact('list_survei','survei','user'));
    }
}
