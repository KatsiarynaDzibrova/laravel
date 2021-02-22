<?php

namespace App\Http\Controllers;


use App\Models\Artist;
use App\Models\Recording;
use Illuminate\Http\Request;

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
     * @return string
     */
    public function getRecordingByArtist($artist_name) {
        $artist = Artist::where('name', $artist_name)->firstOrFail();
        $recording = Recording::where('artist', $artist->id)->get();
        return $recording->toJson();
    }

    /**
     * @param Request $request
     */
    public function createRecording(Request $request) {
        Recording::create($request->all());
    }
}
