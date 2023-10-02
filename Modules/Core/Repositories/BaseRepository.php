<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Facades\DB;

abstract class BaseRepository {

    public $model;
    public $tableName;

    public function fetchAll(object $request, array $with = []): object
    {
        $query = "SELECT * FROM $this->tableName";

        if (!empty($with)) {
            // Assuming $with is an array of related tables or columns
            $query .= " WITH " . implode(", ", $with);
        }

        $result = DB::select($query);

        // Assuming $result is an array of stdClass objects
        $fetched = array_map(function ($item) {
            return (object) get_object_vars($item);
        }, $result);

        return (object) $fetched;
    }

    public function fetch(int|string $id, array $with = []): object
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $bindings = ['id' => $id];

        if (!empty($with)) {
            // Assuming $with is an array of related tables or columns
            $query .= " WITH " . implode(", ", $with);
        }

        $result = DB::select($query, $bindings);

        if (!empty($result)) {
            return (object) $result[0];
        }

        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }

    public function store(string $query, array $bindings): object
    {
        DB::beginTransaction();

        try {
            DB::insert($query, $bindings);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // Assuming you have a way to determine the primary key ID
        $lastInsertedId = DB::getPdo()->lastInsertId();

        $selectQuery = "SELECT * FROM your_table_name WHERE id = ?";
        $result = DB::select($selectQuery, [$lastInsertedId]);

        if (!empty($result)) {
            return (object) $result[0];
        }

        throw new \Exception("Failed to retrieve the inserted record.");
    }

    public function updateUsingRawQuery(array $data, int $id): object
    {
        DB::beginTransaction();

        try {
            $query = "UPDATE your_table_name SET ";

            $bindings = [];

            foreach ($data as $column => $value) {
                $query .= "$column = ?, ";
                $bindings[] = $value;
            }

            $query = rtrim($query, ', '); // Remove the trailing comma and space
            $query .= " WHERE id = ?";
            $bindings[] = $id;

            DB::update($query, $bindings);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // Assuming you have a way to determine the primary key ID
        $result = DB::table('your_table_name')
            ->where('id', '=', $id)
            ->first();

        if ($result) {
            return (object) $result;
        }

        throw new \Exception("Failed to retrieve the updated record.");
    }

    public function deleteUsingRawQuery(string $table, string $primaryKey, int|string $id): object
    {
        DB::beginTransaction();

        try {
            $query = "DELETE FROM $table WHERE $primaryKey = ?";
            $bindings = [$id];

            DB::statement($query, $bindings);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return new \stdClass(); // Return an empty object to match the original return type.
    }
}