<?php

namespace App;

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
     * @string
     */
    private $artist;

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
