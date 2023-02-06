<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $chapters = Chapter::orderBy('order', 'asc')->get();
        return view('layout.dashboard', [
            'dashActive' => true,
            'tempActive' => false,
            'styleActive' => false,
            'welcomeActive' => false,
            'downloadActive' => false,
            'chapters' => $chapters,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function addChapter(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        if (Chapter::exists()) {
            $order = Chapter::max('order') + 1;
        } else {
            $order = 0;
        }
        Chapter::create([
            'order' => $order,
            'title' => $request->title
        ]);

        return back();
    }


    public function swapChapters(Request $request)
    {
        $firstIndex = Chapter::where('order', $request->fromIndex)->first();
        $secondIndex = Chapter::where('order', $request->toIndex)->first();
        if ($firstIndex && $secondIndex)
            $swap  = Chapter::where('id', $firstIndex->id)->update(array('order' => $request->toIndex)) &&
                Chapter::where('id', $secondIndex->id)->update(array('order' => $request->fromIndex));
        if ($swap) {
            $chapters = Chapter::orderBy('order', 'asc')->get();

            return response()->json([
                'status' => 'Success',
                'chapters' => array_values($chapters->toArray())
            ], 201);
        } else {
            return response('Error', 406);
        }
    }


    public function toggleTheme(Request $request) {
        $setting = Setting::where('key', $request->key)->first()->update(array('value'=> $request->value));
        if($setting){
            session([$request->key => $request->value]);
            return response()->json(['status' => 'Success'],201);
        } else {
            return response('Error', 406);
        }
    }
}
