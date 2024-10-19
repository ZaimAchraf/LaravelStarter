<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class UploadController extends Controller
{
    public static function creditDoc(Request $request)
    {

        $file = $request->file('documents');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "credit-".uniqid(time()).".".$extension;
        $file->move('uploads/credits/',$filename);

        return $filename;
    }

    public static function CInvoiceDoc(Request $request)
    {

        $file = $request->file('document');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "facture-achats-".uniqid(time()).".".$extension;
        $file->move('uploads/achats/facture/',$filename);

        return $filename;
    }

    public static function folderDocs($file, $folder, $type)
    {
        $folderPath = 'uploads/dossiers_clients/' . $folder->client->name . '/';

        // Check if directory exists, if not, create it
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);  // Create directory with full permissions if it doesn't exist
        }
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Handle file upload
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid($originalName . '-') . '.' . $extension;
        $file->move($folderPath, $filename);

        return $filename;
    }

    public static function supplierCreditDoc(Request $request)
    {

        $file = $request->file('documents');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "credit-".uniqid(time()).".".$extension;
        $file->move('uploads/supplier_credits/',$filename);

        return $filename;
    }

    public static function deliveryNoteDoc(Request $request)
    {

        $file = $request->file('documents');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = "delivery_notes-".uniqid(time()).".".$extension;
        $file->move('uploads/delivery_notes/',$filename);

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
