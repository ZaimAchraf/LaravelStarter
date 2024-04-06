<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class UploadController extends Controller
{

    public static function seriePic(Request $request)
    {

        $file=$request->file('images')[0];
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "serie-".uniqid(time()).".".$extension;
        $file->move('uploads/series/',$filename);

        return $filename;
    }

    public static function userPic(Request $request)
    {

        $file=$request->file('images');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "user-".uniqid(time()).".".$extension;
        $file->move('uploads/users/',$filename);

        return $filename;
    }

    public static function BoxPics(Request $request)
    {

        $files = array();
        foreach ($request->file('images') as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = "box-" . uniqid(time()) . "." . $extension;
            $file->move('uploads/boxes/', $filename);

            $files[] = $filename;
        }

        return $files;
    }

    public static function coursePic(Request $request)
    {

        $file=$request->file('images');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "course-".uniqid(time()).".".$extension;
        $file->move('uploads/prospects/',$filename);

        return $filename;
    }
}
