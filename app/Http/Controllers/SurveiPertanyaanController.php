<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveiPertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveiPertanyaanController extends Controller
{
    public function index(Survey $surveirw)
    {
        $list_survei = SurveiPertanyaan::where('survei_id',$surveirw->id)->whereNull('deleted_at')->orderBy('created_at','ASC')->get();
        return view('surveirw.index_pertanyaan',compact('list_survei','surveirw'));
    }
    public function create(Survey $surveirw)
    {
        $action = route('surveirw.pertanyaan.store',$surveirw->id);
        return view('surveirw.form_pertanyaan',compact('action','surveirw'));
    }
    public function store(Request $request, Survey $surveirw)
    {
        $request->validate([
            'pertanyaan' => 'required'
        ],[
            'pertanyaan.required'    => 'Pertanyaan survei harus diisi'
        ]);

        try {
            DB::beginTransaction();
            $request['survei_id']   = $surveirw->id;
            SurveiPertanyaan::create($request->except(['_token']));
            DB::commit();
            return redirect()->route('surveirw.pertanyaan.index',$surveirw->id)->with('success','Berhasil menambahkan pertanyaan '.$surveirw->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
    public function edit(Survey $surveirw, SurveiPertanyaan $surveipertanyaan)
    {
        $action = route('surveirw.pertanyaan.update',[$surveirw->id, $surveipertanyaan->id]);
        return view('surveirw.form_pertanyaan',compact('action','surveirw','surveipertanyaan'));
    }
    public function update(Request $request, Survey $surveirw, SurveiPertanyaan $surveipertanyaan)
    {
        $request->validate([
            'pertanyaan' => 'required'
        ],[
            'pertanyaan.required'    => 'Pertanyaan survei harus diisi'
        ]);

        try {
            DB::beginTransaction();
            SurveiPertanyaan::where('id',$surveipertanyaan->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('surveirw.pertanyaan.index',$surveirw->id)->with('success','Berhasil merubah pertanyaan '.$surveirw->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
    public function destroy(Request $request, Survey $surveirw, SurveiPertanyaan $surveipertanyaan)
    {
        try {
            DB::beginTransaction();
            SurveiPertanyaan::where('id',$surveipertanyaan->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('surveirw.pertanyaan.index',$surveirw->id)->with('success','Berhasil menghapus pertanyaan '.$surveirw->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
