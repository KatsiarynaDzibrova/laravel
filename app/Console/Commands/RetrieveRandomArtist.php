<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Recording;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "User-agent: Base ( dzibrovak@famcs-steps.yaconnect.com )\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $artists = json_decode(file_get_contents(
            'http://musicbrainz.org/ws/2/artist/?query=country:de&fmt=json', false, $context), true);
        $artist_id = rand(0, count($artists['artists']));
        $artist_json = $artists['artists'][$artist_id];
        $artist = Artist::addArtist($artist_json);
        $recordings = json_decode(file_get_contents(
            'http://musicbrainz.org/ws/2/recording/?query=artist:' . urlencode($artist_json['name']) . '&fmt=json', false, $context), true);
        foreach ($recordings['recordings'] as $recording) {
            Recording::addRecording($recording, $artist->id);
        }
        return 0;
    }
}
