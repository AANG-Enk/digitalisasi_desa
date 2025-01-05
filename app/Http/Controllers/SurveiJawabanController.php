<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveiJawaban;
use App\Models\SurveiPertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveiJawabanController extends Controller
{
    public function index(Survey $survei)
    {
        $user = auth()->user();
        $list_survei = SurveiPertanyaan::with(['jawabans' => function($q)use($user){
            $q->where('user_id',$user->id);
        }])->where('survei_id',$survei->id)->get();
        $action = route('surveirw.jawaban.store',$survei->slug);
        return view('surveirw.jawaban',compact('list_survei','action','survei','user'));
    }

    public function store(Request $request, Survey $survei)
    {
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string|min:2', // Setiap jawaban harus diisi, string, dan minimal 3 karakter
        ], [
            'jawaban.*.required' => 'Setiap jawaban wajib diisi.',
            'jawaban.*.string' => 'Jawaban harus berupa teks.',
            'jawaban.*.min' => 'Jawaban minimal harus memiliki :min karakter.',
        ]);
        try {
            DB::beginTransaction();
            foreach($request['survei_pertanyaan_id'] as $key => $val){
                SurveiJawaban::updateOrCreate([
                    'user_id'               => auth()->user()->id,
                    'survei_pertanyaan_id'  => $request['survei_pertanyaan_id'][$key],
                ],[
                    'jawaban'               => $request['jawaban'][$key],
                ]);
            }
            DB::commit();
            return redirect()->route('surveirw.jawaban.index',$survei->slug)->with('success','Berhasil menjawab '.$survei->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
