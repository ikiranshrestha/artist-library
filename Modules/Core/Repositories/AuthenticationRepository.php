<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Facades\DB;

class AuthenticationRepository extends BaseRepository{
    public $model = "User";
    public $tableName = "users";
}