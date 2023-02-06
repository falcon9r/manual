<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $welcome = Setting::where('key', 'welcome-page')->first();
        return view('layout.welcome', [
            'dashActive' => false,
            'tempActive' => false,
            'styleActive' => false,
            'welcomeActive' => true,
            'downloadActive' => false,
            'content' => $welcome->value,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function save(Request $request)
    {
        if (Setting::where('key','welcome-page')->update(array('value' => $request->savedData))) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }
}
