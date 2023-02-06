<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Template;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Chapter $chapter)
    {
        $templates = Template::get();
        $topics = $chapter->topics;
        return view('layout.topic', [
            'dashActive' => true,
            'tempActive' => false,
            'styleActive' => false,
            'welcomeActive' => false,
            'downloadActive' => false,
            'darkTheme' => parent::getTheme(),
            'topics' => $topics,
            'chapter' => $chapter,
            'templates' => $templates,
            'recents' => parent::getRecent()
        ]);
    }


    public function addTopic(Chapter $chapter, Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        $temp = Template::where('id', $request->temp)->first();
        if ($temp) {
            $body = $temp->html_body;
        } else {
            $body = " ";
        }
        Topic::create([
            'title' => $request->title,
            'template_id' => $request->temp,
            'html_body' => $body,
            'chapter_id' => $chapter->id,
        ]);

        return back();
    }

    public function removeChapter(Chapter $chapter)
    {
        if (Chapter::where('id', $chapter->id)->delete()) {
            Chapter::where('order', '>', $chapter->order)->decrement('order');
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }


    public function renameChapter(Chapter $chapter, Request $request)
    {
        $this->validate($request, [
            'newName' => 'required'
        ]);
        Chapter::where('id', $chapter->id)->update(array('title' => $request->newName));
        return back();
    }
}
