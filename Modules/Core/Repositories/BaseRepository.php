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

    public function store(array $data): mixed
    {
        DB::beginTransaction();

        try {
            // Construct the SQL INSERT statement
            $sql = "INSERT INTO $this->tableName (";
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_fill(0, count($data), '?'));

            $sql .= "$columns) VALUES ($values)";

            // Execute the raw SQL query with bindings
            $store = DB::insert($sql, array_values($data));
            // Commit the transaction
            DB::commit();

            return $store;
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollback();
            throw $e;
        }
    }

    public function updateUsingRawQuery(array $data, int $id): object
    {
        DB::beginTransaction();

        try {
            $query = "UPDATE $this->tableName SET ";

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
        $result = DB::table('$this->tableName')
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