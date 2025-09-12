<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\role;
use App\Models\proceso;
use App\Models\direction;
use App\Models\area;
use App\Models\union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SgcController extends Controller
{
    public function list()
    {
        $directions = direction::all();
        $areas = area::orderBy('directions_id', 'asc')->get();
        $roles = role::orderBy('area_id', 'asc')->get();
        

    return view('sgcAdmin', compact('directions', 'areas', 'roles'));

    }
}
