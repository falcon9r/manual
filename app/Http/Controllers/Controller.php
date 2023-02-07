<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Setting;
use App\Models\Template;
use App\Models\Topic;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getTheme()
    {
        if (session('settings-dark-theme')) {
            $darkTheme = session('settings-dark-theme');
        } else {
            $darkTheme = Setting::where('key', 'settings-dark-theme')->first()->value;
            session(['settings-dark-theme' => $darkTheme]);
        }
        return $darkTheme;
    }
    public function getRecent()
    {
        // chapters
        $chapters = Chapter::orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // topics
        $topics = Topic::orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        // templates
        $templates = Template::orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $rec = [];
        $latestObj = [];
        for ($counter = 0; $counter < 5; $counter++) {
            $catId = 0;
            $objKey = 0;
            $latestUpdated = date('Y-m-d 00:00:00', strtotime('2000-12-21'));;
            foreach ($chapters as $key => $chap) {
                if ($chap->updated_at > $latestUpdated) {
                    $catId = 0;
                    $objKey = $key;
                    $latestObj = $chap;
                    $latestUpdated = $chap->updated_at;
                }
            }

            foreach ($topics as $key => $topic) {
                if ($topic->updated_at > $latestUpdated) {
                    $catId = 1;
                    $objKey = $key;
                    $latestObj = $topic;
                    $latestUpdated = $topic->updated_at;
                }
            }

            foreach ($templates as $key => $temp) {
                if ($temp->updated_at > $latestUpdated) {
                    $latestObj = $temp;
                    $objKey = $key;
                    $catId = 2;
                    $latestUpdated = $temp->updated_at;
                }
            }

            $result = "";
            if($latestObj == null){
                continue;
            }
            $keyword = $latestObj->updated_at == $latestObj->created_at ? "created" : "altered";
            if ($catId == 0) {
                $result = " has ".$keyword." the chapter named \"".(string)($latestObj->title)."\". ||| ".($latestObj->updated_at->diffForHumans());

                $chapters ->forget($objKey);
            } else if ($catId == 1) {
                $result = " has ".$keyword." the topic named \"".(string)($latestObj->title)."\". ||| ".($latestObj->updated_at->diffForHumans());

                $topics->forget($objKey);
            } else if ($catId == 2) {
                $result = " has ".$keyword." the template named \"".(string)($latestObj->title)."\". ||| ".($latestObj->updated_at->diffForHumans());

                $templates->forget($objKey);
            }
            array_push( $rec, $result);
        }

        return $rec;
    }

}
