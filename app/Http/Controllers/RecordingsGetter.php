<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Recording;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordingsGetter extends Controller
{
    public function __invoke() {
        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "User-agent: MusicBase ( me@example.com )\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        for ($x = 0; $x < 500; ++$x) {
            $id = $this->generate_id();
            try {
                $response = file_get_contents('https://musicbrainz.org/ws/2/recording/' . $id . '?inc=aliases+artist-credits', false, $context);
                $xml = simplexml_load_string($response) or error_log("Error: Cannot create object");
                $recording = $xml->recording;
                $artists = DB::select("select * from artists where MBID = '" . $recording->{'artist-credit'}->{'name-credit'}->artist['id'] . "'", array(1));
                if (empty($artists)) {
                    $response = file_get_contents('https://musicbrainz.org/ws/2/artist/' . $recording->{'artist-credit'}->{'name-credit'}->artist['id'] . '?inc=aliases', false, $context);
                    $xml = simplexml_load_string($response) or error_log("Error: Cannot create object");
                    $artist = $xml->artist;
                    Artist::add_artist($artist);
                    $artists = DB::select("select * from artists where MBID = '" . $recording->{'artist-credit'}->{'name-credit'}->artist['id'] . "'", array(1));
                }
                $artist = $artists[0];
                Recording::add_recording_from_xml($recording, $artist);
            } catch (Exception $e) {
                echo($e);
            }

        }
    }

    private function generate_id() {
        $id = '';
        $lens = [8, 4, 3, 4, 12];
        foreach ($lens as $len) {
            if (strlen($id) == 14) {
                $id .= '4';
            }
            for ($j = 0; $j < $len; ++$j) {
                $id .= base_convert(rand(0, 15), 10, 16);
            }
            $id .= '-';
        }
        $id = substr($id, 0, -1);
        return $id;
    }
}
