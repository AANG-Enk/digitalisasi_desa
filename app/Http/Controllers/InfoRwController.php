<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InfoRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\Jobs\SendInfoRWNotification;

class InfoRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_info_rw = InfoRw::whereNull('deleted_at')->get();
        return view('inforw.index',compact('list_info_rw'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('inforw.store');
        return view('inforw.form',compact('action'));
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

        ini_set('max_execution_time', '0');
		ini_set('memory_limit', '-1');

        try {
            DB::beginTransaction();
            $info   = InfoRw::create($request->except(['_token']));
            User::chunk(200, function($users) use($info){
                dispatch(new SendInfoRWNotification($users, $info));
            });
            // SendInfoRWNotification::dispatch($info->judul, $info->deskripsi, $info->slug);
            DB::commit();
            return redirect()->route('inforw.index')->with('success','Berhasil menambahkan informasi '.$request->judul);
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
    public function show($slug)
    {
        $inforw = InfoRw::where('slug',$slug)->firstOrFail();
        return view('inforw.detail',compact('inforw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoRw $inforw)
    {
        $action = route('inforw.update',$inforw->id);
        return view('inforw.form',compact('action','inforw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfoRw $inforw)
    {
        $request->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
        ],[
            'judul.required'    => 'Judul Informasi harus diisi',
            'deskripsi.required'    => 'Keterangan Informasi harus diisi',
        ]);

        $judul = $inforw->judul;
        try {
            DB::beginTransaction();
            if($request->judul != $inforw->judul){
                $request['slug'] = SlugService::createSlug(InfoRw::class, 'slug', $request->judul);
            }
            InfoRw::where('id',$inforw->id)->update($request->except(['_token','_method']));
            DB::commit();
            return redirect()->route('inforw.index')->with('success','Berhasil merubah informasi '.$judul);
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
    public function destroy(InfoRw $inforw)
    {
        $judul = $inforw->judul;
        try {
            DB::beginTransaction();
            InfoRw::where('id',$inforw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('inforw.index')->with('success','Berhasil menghapus informasi '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
