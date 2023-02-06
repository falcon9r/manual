<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view ('auth.login');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('login')
            ->withErrors($validator)
            ->withInput();
        }

        if(!auth()->attempt($request->only('email','password'), $request->remember)){
            return back()->with('status','invalid login details')->withInput();
        }

        return redirect()->route('dashboard');
    }
}
