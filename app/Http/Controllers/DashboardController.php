<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\IklanRW;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $list_iklan = IklanRw::whereNull('deleted_at')->whereDate('start', '<=', \Carbon\Carbon::today())->whereDate('end', '>=', \Carbon\Carbon::today())->orderBy('start','ASC')->get();
        return view('dashboard.index',compact('list_iklan'));
    }
}
