<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class StyleController extends Controller
{

    public function index()
    {

        $settings = Setting::all()->forget(0)->forget(1);

        return view('layout.style', [
            'dashActive' => false,
            'tempActive' => false,
            'styleActive' => true,
            'welcomeActive' => false,
            'downloadActive' => false,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent(),
            'settings' => $settings
        ]);
    }


    public function saveData(Request $request) {
        $success = true;
        foreach($request->data as $key => $value) {
            $success = Setting::where('key', $key)->update(array('value' => $value)) && $success;
        }

        if ($success) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }

    public function parseCss(String $css)
    {
        $a = array();
        preg_match_all('/^\s*([^:]+)(:\s*(.+))?;\s*$/m', $css, $matches, PREG_SET_ORDER);
        foreach ($matches as $match)
            $a[$match[1]] = isset($match[3]) ? $match[3] : null;
        return $a;
    }
}
