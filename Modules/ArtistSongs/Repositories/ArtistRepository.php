<?php

namespace Modules\ArtistSongs\Repositories;

use Modules\Core\Repositories\BaseRepository;

class ArtistRepository extends BaseRepository{
    public $model = "Artist";
    public $tableName = "artists";
}