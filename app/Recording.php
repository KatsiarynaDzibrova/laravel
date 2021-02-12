<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    private $title;
    private $artist;
    private $length;
    private $ISRC;
    private $MBID;
    private $comment;
    private $annotation;

    public function __get($key) {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    public function __set($key, $value): Recording
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }

        return $this;
    }
}
