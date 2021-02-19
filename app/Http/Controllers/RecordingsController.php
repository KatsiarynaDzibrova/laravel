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

    /**
     * @param $artist_name
     * @return mixed
     */
    public function getRecordingByArtist($artist_name) {
        $artist = Artist::where('name', $artist_name)->firstOrFail();
        $recording = Recording::where('artist', $artist->id)->get();
        return $recording->toJson();
    }
}
