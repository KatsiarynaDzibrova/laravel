<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    private $name;
    private $sort_name;
    private $type;
    private $gender;
    private $area;
    private $begin_end_dates;
    private $IPI_code;
    private $ISNI_code;
    private $alias;
    private $MBID;
    private $comment;
    private $annotation;

    public function __get($key) {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    public function __set($key, $value): Artist
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }

        return $this;
    }
}
