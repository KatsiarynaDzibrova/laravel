<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recording
 * @package App
 */
class Recording extends Model
{
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
            return $this->$key;
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
            $this->$key = $value;
        }

        return $this;
    }
}
