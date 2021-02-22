<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Recording;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RetrieveRecordings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordings:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(): int
    {
        try {
            DB::statement("SET foreign_key_checks=0");
            Recording::truncate();
            Artist::truncate();
            DB::statement("SET foreign_key_checks=1");

            $response_artist = Http::withHeaders([
                'User-Agent' => env('APP_NAME') . ' ( ' . env('CONTACT_MAIL') . ' )'
            ])->get(self::API_BASE_URL . 'artist/?query=country:gb%20AND%20tag:rock%20AND%20type:group&fmt=json');
            $artists = json_decode($response_artist, true);
            foreach ($artists['artists'] as $el => $artist_json) {
                $artist = Artist::addArtist($artist_json);
                $response_recordings = Http::withHeaders([
                    'User-Agent' => env('APP_NAME') . ' ( ' . env('CONTACT_MAIL') . ' )'
                ])->get(self::API_BASE_URL . 'recording/?query=artist:' . urlencode($artist_json['name']) . '&fmt=json');
                $recordings = json_decode($response_recordings, true);
                Recording::addMultipleRecordings($recordings['recordings'], $artist->id);
                sleep(1);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return 1;
        }
        return 0;
    }
}
