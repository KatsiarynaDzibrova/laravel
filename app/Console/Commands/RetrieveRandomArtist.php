<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Recording;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RetrieveRandomArtist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artists:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create random Artists and related Recordings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const API_BASE_URL = 'http://musicbrainz.org/ws/2/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response_artist = Http::withHeaders(['User-Agent' => env('APP_NAME') . ' ( ' . env('CONTACT_MAIL') . ' )'
            ])->get(self::API_BASE_URL . 'artist/?query=country:de&fmt=json');
            $artists = json_decode($response_artist, true);
            $artist_id = rand(0, count($artists['artists']));
            $artist_json = $artists['artists'][$artist_id];
            $artist = Artist::addArtist($artist_json);
            $response_recordings = Http::withHeaders(['User-Agent' => env('APP_NAME') . ' ( ' . env('CONTACT_MAIL') . ' )'
            ])->get(self::API_BASE_URL . 'recording/?query=artist:' . urlencode($artist_json['name']) . '&fmt=json');
            $recordings = json_decode($response_recordings, true);
            Recording::addMultipleRecordings($recordings['recordings'], $artist->id);
        } catch (\Exception $e) {
            Log::error($e);
        }
        return 0;
    }
}
