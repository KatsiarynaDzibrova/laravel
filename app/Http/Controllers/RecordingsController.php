<?php

namespace App\Http\Controllers;


use App\Models\Artist;
use App\Models\Recording;

class RecordingsController extends Controller
{
    /**
     * @return string
     */
    public function getAllRecordings() {
        return Recording::all()->toJson();
    }
}
