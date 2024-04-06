<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Course;

class VitrineController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

}
