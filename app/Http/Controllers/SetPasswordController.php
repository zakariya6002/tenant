<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePasswordrequest;

class SetPasswordController extends Controller
{
    public function create(){
        return view('auth.setpassword');
    }
    public function store(StorePasswordrequest $request)
    {
        auth()->user()->update([
            'password' => bcrypt($request->password)
        ]);
        return redirect()->route('dashboard')->with('status','Password Set Successfully');
    }
}
