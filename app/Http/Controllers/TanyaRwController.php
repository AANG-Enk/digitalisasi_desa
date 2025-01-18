<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TanyaRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendTanyaRWNotification;

class TanyaRwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('Warga') && !auth()->user()->ketua_rw){
            $list_tanya_rw = TanyaRw::where('user_id',auth()->user()->id)->whereNull('deleted_at')->orderBy('created_at','ASC')->get();
            $item_tanya_rw = TanyaRw::whereNull('warga_text')->where([['user_id',auth()->user()->id],['is_read',false]])->latest()->first();
            if(!is_null($item_tanya_rw)){
                $item_tanya_rw->update(['is_read' => true]);
            }
            $pak_rw         = User::where([['rw', auth()->user()->rw],['ketua_rw',true]])->first();
            $action = route('tanyarw.store');
            return view('tanyarw.detail',compact('list_tanya_rw','action','pak_rw'));
        }else{
            $list_tanya_rw = TanyaRw::with('pembuat')
                            ->whereNull('deleted_at')
                            ->orderBy('created_at', 'ASC')
                            ->get()
                            ->groupBy('user_id')
                            ->map(function ($items) {
                                return $items->last(); // Ambil item terakhir dalam setiap grup
                            });
            return view('tanyarw.index',compact('list_tanya_rw'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text'  => 'required',
        ],[
            'text.required' => 'Pesan harus diisi'
        ]);

        try {
            DB::beginTransaction();
            $pak_rw                     = User::where([['rw', auth()->user()->rw],['ketua_rw',true]])->first();
            $request['user_id']         = auth()->user()->id;
            $request['warga_text']      = $request['text'];
            $tanya = TanyaRw::create($request->except(['_token','text']));
            dispatch(new SendTanyaRWNotification($pak_rw, $request['text'], auth()->user()->name));
            DB::commit();
            return redirect()->route('tanyarw.index')->with('success','Berhasil megirim pesan ');
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
    public function show(TanyaRw $tanyarw)
    {
        $list_tanya_rw = TanyaRw::where('user_id',$tanyarw->user_id)->whereNull('deleted_at')->orderBy('created_at','ASC')->get();
        $item_tanya_rw = TanyaRw::whereNull('rw_text')->where('user_id',$tanyarw->user_id)->latest()->first();
        if(!is_null($item_tanya_rw)){
            $item_tanya_rw->update(['is_read' => true]);
        }
        $pak_rw         = User::where([['rw', auth()->user()->rw],['ketua_rw',true]])->first();
        $action = route('tanyarw.update',$tanyarw->id);
        return view('tanyarw.detail',compact('list_tanya_rw','action','tanyarw','pak_rw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TanyaRw $tanyarw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TanyaRw $tanyarw)
    {
        $request->validate([
            'text'  => 'required',
        ],[
            'text.required' => 'Pesan harus diisi'
        ]);

        try {
            DB::beginTransaction();
            $user                       = User::where('id',$tanyarw->user_id)->first();
            $request['user_id']         = $tanyarw->user_id;
            $request['rw_text']         = $request['text'];
            $tanya = TanyaRw::create($request->except(['_token','text','_method']));
            dispatch(new SendTanyaRWNotification($user, $request['text'], auth()->user()->name));
            DB::commit();
            return redirect()->route('tanyarw.show',$tanyarw->id)->with('success','Berhasil megirim pesan ');
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
    public function destroy(TanyaRw $tanyarw)
    {
        //
    }
}
