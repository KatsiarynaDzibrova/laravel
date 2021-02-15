<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    /**
     * @string
     */
    private $name;

    /**
     * @string
     */
    private $sort_name;

    /**
     * A stringiant of the artist name which would be used when sorting artists by name
     *
     * @string
     */
    private $type;

    /**
     * Person, Group, Orchestra, Choir. Character or Other
     *
     * @string
     */
    private $gender;

    /**
     * Male, female or neither. Groups do not have genders.
     *
     * @string
     */
    private $area;

    /**
     * @string
     */
    private $begin_end_dates;

    /**
     * @string
     */
    private $IPI_code;

    /**
     * @string
     */
    private $ISNI_code;

    /**
     * @string
     */
    private $alias;

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
    public function __set($key, $value): Artist
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }

        return $this;
    }
}
