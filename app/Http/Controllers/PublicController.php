<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Setting;
use App\Models\Temlate;
use App\Models\Topic;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $style = Setting::all();
        $css = '';
        foreach ($style as $property) {
            $css .= $property->key.": ".$property->value."; ";
        }
        $welcome = Setting::where('key', 'welcome-page')->first();
        $chapters = Chapter::orderBy('order', 'asc')->get();
        $topics = Topic::where(['status' => 1])->get();
        $groupedTopics = $topics->groupBy('chapter_id');
        return view('public.main', [
            'welcome' => $welcome->value,
            'groupedTopics' => $groupedTopics,
            'chapters' => $chapters,
            'topics' => $topics,
            'style' => $css
        ]);
    }
}
