<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\powerbi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    
    public function tableros()
    {
        $id = Auth::user()->id;

        $tableros = powerbi::orderBy('nombre')->where('user_id', $id)->get();
        return view('tableros' , compact('tableros'));
    }
    public function usuarioalta(){
        return view('auth.register');
    }

}
