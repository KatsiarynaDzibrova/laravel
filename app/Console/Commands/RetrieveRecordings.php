<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Recording;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        DB::statement("SET foreign_key_checks=0");
        Recording::truncate();
        Artist::truncate();
        DB::statement("SET foreign_key_checks=1");
        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "User-agent: Base ( dzibrovak@famcs-steps.yaconnect.com )\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $artists = json_decode(file_get_contents(
            'http://musicbrainz.org/ws/2/artist/?query=country:gb%20AND%20tag:rock%20AND%20type:group&fmt=json', false, $context), true);
        foreach ($artists['artists'] as $el => $artist_json) {
            $artist = Artist::addArtist($artist_json);
            $recordings = json_decode(file_get_contents(
                'http://musicbrainz.org/ws/2/recording/?query=artist:' . urlencode($artist_json['name']) . '&fmt=json', false, $context), true);
            foreach ($recordings['recordings'] as $recording) {
                Recording::addRecording($recording, $artist->id);
            }
            sleep(1);
        }
        return 0;
    }
}
