<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $userData = User::get()->first();
        return view('layout.profile', [
            'dashActive' => false,
            'tempActive' => false,
            'styleActive' => false,
            'welcomeActive' => false,
            'downloadActive' => false,
            'userData' => $userData,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        if ($request->password == "previouspassword") {
            if (User::where('id', 1)->update(array('email' => $request->email))) {
                return redirect()->back()->withSuccess('Success');
            } else {
                return redirect()->back()->withError('Error');
            }
        } else {
            if (User::where('id', 1)->update(array('email' => $request->email, 'password' => Hash::make($request->password)))) {
                return redirect()->back()->withSuccess('Success');
            } else {
                return redirect()->back()->withError('Error');
            }
        }
    }
}
