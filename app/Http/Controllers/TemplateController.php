<?php

namespace App\Http\Controllers;
use App\Models\Template;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::get();
        return view('layout.template', [
            'dashActive'=> false,
            'tempActive'=> true,
            'styleActive'=> false,
            'welcomeActive' => false,
            'downloadActive'=> false,
            'templates' => $templates,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function addTemp(Request $request){
        $this->validate($request, [
            'title' => 'required'
        ]);
        Template::create([
            'title' => $request->title
        ]);

        return back();
    }
}
