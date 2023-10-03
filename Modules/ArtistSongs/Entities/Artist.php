<?php

namespace Modules\ArtistSongs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "dob",
        "gender",
        "address",
        "first_year_release",
        "no_of_albums_released",
    ];
}
