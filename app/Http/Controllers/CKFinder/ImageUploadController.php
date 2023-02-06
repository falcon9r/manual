<?php

namespace App\Http\Controllers\CKFinder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('userfiles/files' , [
                'disk' => 'public'
            ]);
            $url = asset('storage/' . $path);
            return response()->json(['fileName' => 'file', 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
