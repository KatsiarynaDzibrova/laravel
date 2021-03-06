<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recording
 * @package App
 */
class Recording extends Model
{
    protected $fillable = ['title', 'length', 'ISRC', 'MBID', 'comment',
        'annotation','artist'];

    /**
     * @string
     */
    private $title;

    /**
     * @int
     */
    private $length;

    /**
     * Entered manually for standalone recordings
     *
     * @string
     */
    private $ISRC;

    /**
     * @string
     */
    private $MBID;

    /**
     * @string
     */
    private $comment;

    /**
     * @string
     */
    private $annotation;

    /**
     * Get the artist associated with the user.
     */
    public function artist() {
        return $this->hasOne(Artist::class);
    }

    private static function recordingDataRetrieve($recording, $artist_id) {
        return [
            'title' => $recording['title'],
            'length' => $recording['length'],
            'MBID' => $recording['id'],
            'artist' => $artist_id,
        ];
    }

    public static function addRecording($recording, $artist_id) {
        if (!array_key_exists('length', $recording)) {
            $recording['length'] = NULL;
        }
        return Recording::create(self::recordingDataRetrieve($recording, $artist_id));
    }

    public static function addMultipleRecordings($recordings, $artist_id) {
        $recordings_data = [];
        foreach ($recordings as $recording) {
            if (!array_key_exists('length', $recording)) {
                $recording['length'] = NULL;
            }
            array_push($recordings_data, self::recordingDataRetrieve($recording, $artist_id));
        }
        return Recording::insert($recordings_data);
    }
}
