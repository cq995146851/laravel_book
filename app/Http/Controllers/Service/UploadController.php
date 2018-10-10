<?php

namespace App\Http\Controllers\Service;

use App\Models\M3Result;
use App\Tool\UUID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function uploadFile (Request $request) {
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();     // 扩展名
        $uuid = UUID::create();
        $filename = date('Ymd') . '-' . $uuid . '.' . $ext;
        $move_path = 'admin/upload';
        $path = $file->move($move_path, $filename);
        $path = str_replace('\\','/',url($path));
        return $path;
    }
}
