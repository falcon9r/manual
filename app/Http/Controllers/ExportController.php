<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Setting;
use App\Models\Topic;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use ZipArchive;

class ExportController extends Controller
{
    public function index()
    {
        $style = Setting::all()->forget(0)->forget(1);
        $css = '';
        foreach ($style as $property) {
            $css .= $property->key . ": " . $property->value . "; ";
        }
        $chapters = Chapter::orderBy('order', 'asc')->get();
        $topics = Topic::where(['status' => 1])->get();
        $groupedTopics = $topics->groupBy('chapter_id');
        $welcome = Setting::where('key', 'welcome-page')->first();

        $data = array();
        array_push($data, '<div class="content">' . ($welcome->value) . '</div>');
        $tt = collect();
        $tt->add(new Topic(["title" => "Welcome"]));

        $content = view('public.template', [
            'topics' => $tt,
            'title' => "Tiger Manual",
            'Data' => $data,
            'style' => $css
        ])->render();
        Storage::put('tempDir/0_Welcome.html' , $content);

        $counter = 1;
        foreach ($chapters as $chapter) {
            if (isset($groupedTopics[$chapter->id])) {
                $data = array();
                foreach ($groupedTopics[$chapter->id] as $topic) {
                    array_push($data, '<div class="content">' . ($topic->html_body) . '</div>');
                }
                $content = view('public.template', [
                    'topics' => $groupedTopics[$chapter->id],
                    'title' => $chapter->title,
                    'Data' => $data,
                    'style' => $css
                ])->render();
                Storage::put('tempDir/' .$counter."_". str_replace(' ', '', ucwords($chapter->title) . ".html"), $content);
                $counter += 1;
            }
        }

        $zip_file = 'OfflineManual.zip';
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach (Storage::allFiles('tempDir') as $file) {
            $name = basename($file);
            $zip->addFile(storage_path('app/tempDir/' . $name), $name);
        }
        $zip->addFile(public_path('css/offline/styler.css'), 'resources/offline.css');
        $zip->addFile(public_path('js/ckeditor/ckeditor.js'), 'resources/ckeditor.js');
        $zip->close();
        Storage::deleteDirectory('tempDir');


        return response()->download($zip_file);
    }

    public function jsonToHtml($data)
    {
        $data = json_decode($data)->blocks;

        $ret = '';
        foreach ($data as $item) {
            switch ($item->type) {
                case 'header':
                    $levelSize = $item->data->level;
                    $levelText = $item->data->text;
                    $ret .= "<a class='anchor' id='".str_replace(' ', '', ucwords($levelText))."'></a>";
                    $ret .= "<h{$levelSize} class=" . '"header"' . "> $levelText </h{$levelSize}> ";
                    break;

                case 'paragraph':
                    $levelText = $item->data->text;
                    $ret .= '<p class="paragraph block">' . $levelText . "</p>";
                    break;
                case 'list':
                    $levelStyle = $item->data->style === 'unordered' ? 'ul' : 'ol';
                    $levelArr = $item->data->items;

                    $list = "<$levelStyle class=" . '"list block">';
                    $listItems = "";
                    foreach ($levelArr as $eleItem) {
                        $listItems .= "<li class=" . '"list-item"' . "> $eleItem </li>";
                    }
                    $list .= $listItems;
                    $list .= "</$levelStyle>";

                    $ret .= $list;
                    break;
                case 'checklist':
                    $checkList = "";
                    $levelArr = $item->data->items;
                    foreach ($levelArr as $eleItem) {
                        $itemStyle = $eleItem->checked == true ? "checked" : "";
                        $checkList  .= '<div class="checklist-item block ' . $itemStyle . '"><span class="checklist-box"></span><div class="checklist-text">' . $eleItem->text . '</div></div>';
                    }
                    $ret .= $checkList;
                    break;
                case 'image':
                    $levelClass =  ($item->data->stretched ? " img-fullwidth" : ""). ($item->data->withBorder ? " img-border" : "") . ($item->data->withBackground ? " img-bg" : "");
                    $levelFilePath = $item->data->url;
                    $levelCaption = $item->data->caption;
                    $ret .= '<figure class="fig-img'. $levelClass. '">';
                    $ret .= '<img class="img'.$levelClass.'" alt="' . $levelCaption . '" src="' . $levelFilePath . '">';
                    $ret .= '<figcaption style="padding: 0.5rem;">' . $levelCaption . '</figcaption>';
                    $ret .= '</figure>';
                    break;
                case 'table':
                    $tableHtml = '<table class="table block">';
                    $heading = $item->data->withHeadings == true;

                    foreach ($item->data->content as $row) {
                        $thtd = $heading ? "th" : "td";
                        $tableHtml .= "<tr>";
                        foreach ($row as $column) {
                            $cell = $column == null ? "" : '<div style="padding: 8% 12%;">' . $column . "</div>";
                            $tableHtml .= "<$thtd>$cell</$thtd>";
                        }
                        $heading = false;
                        $tableHtml .= "</tr>";
                    }
                    $tableHtml .= "</table>";

                    $ret .= $tableHtml;
                    break;
                case 'code':
                    $code = $item->data->code;
                    $ret .= '<div class="pre block">' . $code . "</div>";
                    break;
                case 'warning':
                    $title = $item->data->title;
                    $message = $item->data->message;
                    $ret .= '<div class="warning block"><div>';
                    $ret .= '<svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">';
                    $ret .= '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd">';
                    $ret .= '</path></svg>';
                    $ret .= '<h3>' . $title . '</h3></div><p>' . $message . '</p></div>';
                    break;
                case 'tip':
                    $title = $item->data->title;
                    $message = $item->data->message;
                    $ret .= '<div class="tip block"><div>';
                    $ret .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">';
                    $ret .= '<g><path stroke="black" stroke-width="40" d="M500,210.2c-74.5,0-144.6,28.7-197.3,80.9C250,343.3,221,412.7,221,486.5c0,73.8,29,143.2,81.7,195.3c52.7,52.2,122.8,80.9,197.3,80.9s144.6-28.7,197.3-80.9C750,629.7,779,560.3,779,486.5c0-73.8-29-143.2-81.7-195.4C644.7,238.9,574.5,210.2,500,210.2z M500,704.5c-121.4,0-220.3-97.7-220.3-218c0-120.2,98.8-218,220.3-218c121.4,0,220.3,97.8,220.3,218C720.3,606.7,621.5,704.5,500,704.5z M503.2,138.8c16.2,0,29.4-13,29.4-29.2V39.2c0-16.1-13.2-29.2-29.4-29.2c-16.2,0-29.5,13-29.5,29.2v70.5C473.8,125.8,487,138.8,503.2,138.8z M256,237.6c7.6,0,15.1-2.9,20.8-8.6c11.5-11.4,11.5-29.8,0-41.2l-50.3-49.9c-11.5-11.4-30.1-11.4-41.6,0c-11.5,11.4-11.5,29.8,0,41.2l50.3,49.8C240.9,234.7,248.4,237.6,256,237.6z M151.6,422.2H80.4c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C181.1,435.3,167.9,422.2,151.6,422.2z M919.6,428.8h-71.2c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C949.1,441.8,935.9,428.8,919.6,428.8z M748.8,242.2c7.6,0,15.1-2.9,20.8-8.5l50.3-49.9c11.5-11.4,11.5-29.8,0-41.2s-30.1-11.4-41.7,0l-50.4,49.9c-11.5,11.4-11.5,29.8,0,41.2C733.7,239.3,741.1,242.2,748.8,242.2z M608.7,820.9H391.4c-16.2,0-29.5,13.1-29.5,29.2s13.2,29.2,29.5,29.2h217.3c16.2,0,29.5-13,29.5-29.2C638.1,834,624.9,820.9,608.7,820.9z M569.4,931.6H430.7c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h138.6c16.2,0,29.5-13.1,29.5-29.2C598.8,944.7,585.6,931.6,569.4,931.6z"/></g>';
                    $ret .= '</svg>';
                    $ret .= '<h3>' . $title . '</h3></div><p>' . $message . '</p></div>';
                    break;
                case 'quote':
                    $ret .= '<blockquote>
                    <p>';
                    $ret .= $item->data->text;
                    $ret .= "-" . $item->data->caption;
                    $ret .= "</p></blockquote>";
                    break;
                case 'delimiter':
                    $ret .= '<hr class="delimiter"/>';
                    break;
                default:
                    throw new Exception("Unknown Block Type: '" . $item->type . "'");
            }
            $ret .= "\n";
        }
        return $ret;
    }
}
