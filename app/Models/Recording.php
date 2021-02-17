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

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        if (property_exists($this, $key)) {
            return $this->attributes[$key];
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function __set($key, $value): Recording
    {
        if (property_exists($this, $key)) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function get_length_min(): float
    {
        return number_format((float) ($this->attributes['length']) / 60 / 1000, 2);
    }

    public static function add_recording($recording, $artist) {
        Recording::create([
            'title' => $recording->title,
            'length' => $recording->length,
            'ISRC' => $recording->ISRC,
            'MBID' => $recording['id'],
            'comment' => $recording->comment,
            'annotation' => $recording->annotation,
            'artist' => $artist->id,
        ]);
    }
}
