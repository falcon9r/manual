<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Template;
use Illuminate\Http\Request;

class EditorController extends Controller
{

    public function index(Topic $topic)
    {
        $chapter = Chapter::where('id', $topic->chapter_id)->first();
        $template = Template::where('id', $topic->template_id)->first();
        if($template) {
            $templateBody = $template->html_body;
        } else {
            $templateBody = "0";
        }
        return view('layout.ckeditor', [
            'dashActive' => true,
            'tempActive' => false,
            'styleActive' => false,
            'welcomeActive' => false,
            'downloadActive' => false,
            'topic' => $topic,
            'chapter' => $chapter,
            'templateBody' => $templateBody,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function template(Template $template)
    {
        return view('layout.ckeditor', [
            'dashActive' => false,
            'tempActive' => true,
            'styleActive' => false,
            'welcomeActive' => false,
            'downloadActive' => false,
            'template' => $template,
            'darkTheme' => parent::getTheme(),
            'recents' => parent::getRecent()
        ]);
    }

    public function saveTopic(Topic $topic, Request $request)
    {
        if(Topic::where('id', $topic->id)->update(array('html_body' => $request->savedData))) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }

    public function saveTemp(Template $template, Request $request)
    {
        if (Template::where('id', $template->id)->update(array('html_body' => $request->savedData))) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }


    public function removeTopic(Topic $topic)
    {
        if(Topic::where('id', $topic->id)->delete()) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }

    public function removeTemp(Template $template)
    {
        if (Template::where('id', $template->id)->delete()) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }

    public function publishTopic(Topic $topic, Request $request) {
        if(Topic::where('id', $topic->id)->update(array('status' => $request->newState))) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }

    public function publishTemp(Template $template, Request $request) {
        if (Template::where('id', $template->id)->update(array('status' => $request->newState))) {
            return response('Success', 201);
        } else {
            return response('Error', 406);
        }
    }
    public function orderChange(Topic $topic, Request $request)
    {
        $request->validate([
            'order' => 'integer|min:0'
        ]);
        $order = $request->order;
        $HighOrder = Topic::query()->max('order');
        if($order > $HighOrder)
        {
            Topic::query()
                ->where('order' , '>=' , $topic->order)
                ->where('id' ,  '!=', $topic->id)->decrement('order' , 1);
            $topic->order = $HighOrder;
        }
        else
        {
            if($order < $topic->order)
            {
                Topic::query()
                    ->where('order' , '>=' , $order)
                    ->where('order' , '<=', $topic->order)
                    ->where('id' ,  '!=', $topic->id)->increment('order' , 1);
            }
            else if($order > $topic->order)
            {
                Topic::query()
                    ->where('order' , '<=' , $order)
                    ->where('order' , '>=', $topic->order)
                    ->where('id' ,'!=', $topic->id)->decrement('order',  1);
            }
            $topic->order = $order;
        }
        $topic->save();
        return response('Success' , 201);
    }

    public function renameTopic(Topic $topic , Request $request)
    {
        $request->validate([
            'name' => 'string|required'
        ]);
        $topic->update([
            'title' => $request->name
        ]);
        return response('Success' , 201);
    }
}
