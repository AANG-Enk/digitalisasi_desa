<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forum_saya = Forum::whereNull('deleted_at')->where([['bagian','Warga'],['user_id',auth()->user()->id]])->count();
        $list_forum = Forum::whereNull('deleted_at')->where('bagian','Warga')->orderBy('published_at','DESC')->paginate(10);
        return view('forumrw.warga', compact('list_forum','forum_saya'));
    }

    public function index_pengurus()
    {
        $forum_saya = Forum::whereNull('deleted_at')->where([['bagian','Pengurus'],['user_id',auth()->user()->id]])->count();
        $list_forum = Forum::whereNull('deleted_at')->where('bagian','Pengurus')->orderBy('published_at','DESC')->paginate(10);
        return view('forumrw.index', compact('list_forum','forum_saya'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = route('forumrw.store');
        return view('forumrw.form',compact('action'));
    }

    public function create_pengurus()
    {
        $flag = 'pengurus';
        $action = route('forumrw.pengurus.store');
        return view('forumrw.form',compact('action','flag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'required|image|mimes:png,jpg,jpeg|max:5048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.required'        => 'Thumbnail berita harus disi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 5mb',
        ]);

        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('forum-thumbnail','public');
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            $request['bagian']          = 'Warga';
            Forum::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('forumrw.index')->with('success','Berhasil menambahkan thread baru '.$request->judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store_pengurus(Request $request)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'required|image|mimes:png,jpg,jpeg|max:5048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.required'        => 'Thumbnail berita harus disi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 5mb',
        ]);

        try {
            DB::beginTransaction();
            $request['image']           = $request->file('thumb')->store('forum-thumbnail','public');
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            $request['user_id']         = auth()->user()->id;
            $request['bagian']          = 'Pengurus';
            Forum::create($request->except(['_token','thumb']));
            DB::commit();
            return redirect()->route('forumrw.pengurus.index')->with('success','Berhasil menambahkan thread baru '.$request->judul);
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
        $forum = Forum::where('slug',$slug)->firstOrFail();
        return view('forumrw.detail',compact('forum'));
    }

    public function show_pengurus($slug)
    {
        $flag = 'pengurus';
        $forum = Forum::where('slug',$slug)->firstOrFail();
        return view('forumrw.detail',compact('forum','flag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Forum $forumrw)
    {
        $action = route('forumrw.update',$forumrw->id);
        return view('forumrw.form',compact('action','forumrw'));
    }

    public function edit_pengurus(Forum $forumrw)
    {
        $flag = 'pengurus';
        $action = route('forumrw.pengurus.update',$forumrw->id);
        return view('forumrw.form',compact('action','forumrw','flag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Forum $forumrw)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:5048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 5mb',
        ]);

        $judul = $forumrw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($forumrw->image) {
                    Storage::delete($forumrw->image);
                }
                $request['image']    = $request->file('thumb')->store('forum-thumbnail','public');
            }
            if ($request->judul != $forumrw->judul) {
                $request['slug'] = SlugService::createSlug(Forum::class, 'slug', $request->judul);
            }
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            Forum::where('id',$forumrw->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('forum.saya')->with('success','Berhasil merubah thread '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function update_pengurus(Request $request, Forum $forumrw)
    {
        $request->validate([
            'judul'                 => 'required',
            'deskripsi'             => 'required',
            'published_at'          => 'required',
            'thumb'                 => 'image|mimes:png,jpg,jpeg|max:5048',
        ],[
            'judul.required'        => 'Judul harus diisi',
            'deskripsi'             => 'Deskripsi harus diisi',
            'published_at'          => 'Tanggal ditayangkan harus diisi',
            'thumb.image'           => 'Thumbnail berita harus gambar',
            'thumb.mimes'           => 'Thumbnail berita tidak valid',
            'thumb.max'             => 'Thumbnail berita maksimal 5mb',
        ]);

        $judul = $forumrw->judul;
        try {
            DB::beginTransaction();
            if ($request->file('thumb')) {
                if ($forumrw->image) {
                    Storage::delete($forumrw->image);
                }
                $request['image']    = $request->file('thumb')->store('forum-thumbnail','public');
            }
            if ($request->judul != $forumrw->judul) {
                $request['slug'] = SlugService::createSlug(Forum::class, 'slug', $request->judul);
            }
            $request['published_at']    = \Carbon\Carbon::createFromFormat('d-m-Y',$request['published_at'])->format('Y-m-d');
            Forum::where('id',$forumrw->id)->update($request->except(['_token','_method','thumb']));
            DB::commit();
            return redirect()->route('forum.pengurus.saya')->with('success','Berhasil merubah thread '.$judul);
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
    public function destroy(Forum $forumrw)
    {
        $judul = $forumrw->judul;
        try {
            DB::beginTransaction();
            Forum::where('id',$forumrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('forum.saya')->with('success','Berhasil menghapus thread '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy_pengurus(Forum $forumrw)
    {
        $judul = $forumrw->judul;
        try {
            DB::beginTransaction();
            Forum::where('id',$forumrw->id)->update([
                'deleted_at'    => \Carbon\Carbon::now(),
            ]);
            DB::commit();
            return redirect()->route('forum.pengurus.saya')->with('success','Berhasil menghapus thread '.$judul);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function saya()
    {
        $user = auth()->user();
        $forum_saya = Forum::whereNull('deleted_at')->where([['bagian','Warga'],['user_id',auth()->user()->id]])->count();
        $list_forum = Forum::whereNull('deleted_at')->where([['bagian','Warga'],['user_id',auth()->user()->id]])->orderBy('published_at','DESC')->paginate(10);
        return view('forumrw.warga', compact('list_forum','forum_saya','user'));
    }

    public function saya_pengurus()
    {
        $flag = 'pengurus';
        $user = auth()->user();
        $forum_saya = Forum::whereNull('deleted_at')->where([['bagian','Pengurus'],['user_id',auth()->user()->id]])->count();
        $list_forum = Forum::whereNull('deleted_at')->where([['bagian','Pengurus'],['user_id',auth()->user()->id]])->orderBy('published_at','DESC')->paginate(10);
        return view('forumrw.index', compact('list_forum','forum_saya','user'));
    }
}
