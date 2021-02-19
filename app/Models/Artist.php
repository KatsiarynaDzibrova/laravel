<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'sort_name', 'type', 'gender', 'area', 'begin_date', 'MBID', ];

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
     * @int
     */
    private $begin_date;

    /**
     * @int
     */
    private $end_date;

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

    public function recording()
    {
        return $this->belongsTo(Recording::class);
    }

    private static function formatDate ($date) {
        if (strlen($date) == 4) {
            return $date . '-01-01';
        } else if (strlen($date) == 7) {
            return $date . '-01';
        } else {
            return $date;
        }
    }

    public static function addArtist($artist) {
        if (!array_key_exists('gender', $artist)) {
            $artist['gender'] = NULL;
        }
        return Artist::create([
            'name' => $artist['name'],
            'sort_name' => $artist['sort-name'],
            'type' => $artist['type'],
            'gender' => $artist['gender'],
            'begin_date' => Artist::formatDate($artist['life-span']['begin']),
            'MBID' => $artist['id'],
        ]);
    }

}
