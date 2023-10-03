<?php

namespace Modules\Core\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

abstract class BaseRepository {

    public $model;
    public $tableName;
    public PDO $pdo;

    function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=artist_library", "root", "");
    }

    // public function fetchAll(object $request, array $with = []): array
    // {
    //     $query = "SELECT * FROM $this->tableName";

    //     if (!empty($with)) {
    //         // Assuming $with is an array of related tables or columns
    //         $query .= " WITH " . implode(", ", $with);
    //     }

    //     $result = DB::select($query);

    //     // Assuming $result is an array of stdClass objects
    //     $fetched = array_map(function ($item) {
    //         return (object) get_object_vars($item);
    //     }, $result);

    //     return $fetched;
    // }

    public function fetchAll(Request $request, array $with = []): array
    {
        try {
            $query = "SELECT {$this->tableName}.*";

            if (!empty($with)) {
                foreach ($with as $relationTable) {
                    $query .= ", {$relationTable}.* LEFT JOIN {$relationTable} ON {$this->tableName}.id = {$relationTable}.user_id";
                }
            }

            $query .= " FROM {$this->tableName}";

            // Prepare and execute the raw SQL query
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            // Fetch the results as associative arrays
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Assuming you want the results as an array of objects
            $fetched = array_map(function ($item) {
                return (object) $item;
            }, $results);

            return $fetched;
        } catch (PDOException $e) {
            // Handle any exceptions, such as database connection errors
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function fetch(int|string $id, array $with = []): object
    {
        try {
         // Start building the raw SQL query
            $query = "SELECT {$this->tableName}.*";

            if (!empty($with)) {
                foreach ($with as $relationTable) {
                    // Assuming $with contains the related table names
                    // Customize the JOIN conditions as needed based on your schema
                    // For simplicity, we assume that the foreign key in the related table
                    // is named 'user_id' and it's related to the 'id' column of the main table
                    $query .= ", {$relationTable}.* LEFT JOIN {$relationTable} ON {$this->tableName}.id = {$relationTable}.user_id";
                }
            }

            $query .= " FROM {$this->tableName} WHERE {$this->tableName}.id = :id";
            $bindings = ['id' => $id];

            // Prepare and execute the raw SQL query
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($bindings);

            // Fetch the result as an associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                return (object) $result;
            }

            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        } catch (PDOException $e) {
            // Handle any exceptions, such as database connection errors
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function store(array $data): mixed
    {
        try {
            // Begin a transaction
            $this->pdo->beginTransaction();

            // Prepare the SQL statement
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));

            $sql = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);

            // Bind values to placeholders
            $i = 1;
            foreach ($data as $value) {
                $stmt->bindValue($i++, $value);
            }

            // Execute the SQL statement
            $store = $stmt->execute();

            // Commit the transaction
            $this->pdo->commit();

            return $store;
        } catch (PDOException $e) {
            // If an error occurs, roll back the transaction
            $this->pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    public function update(array $data, int $id): object
    {
        try {
            // Begin a transaction
            $this->pdo->beginTransaction();

            $query = "UPDATE $this->tableName SET ";

            $bindings = [];

            foreach ($data as $column => $value) {
                $query .= "$column = ?, ";
                $bindings[] = $value;
            }

            $query = rtrim($query, ', '); // Remove the trailing comma and space
            $query .= " WHERE id = ?";
            $bindings[] = $id;

            // Prepare the SQL statement
            $stmt = $this->pdo->prepare($query);

            // Bind values to placeholders
            $i = 1;
            foreach ($bindings as $value) {
                $stmt->bindValue($i++, $value);
            }

            // Execute the SQL statement
            $stmt->execute();

            // Check if a transaction is active before committing
            if ($this->pdo->inTransaction()) {
                // Commit the transaction
                $this->pdo->commit();
            }

            // Retrieve the updated record
            $selectQuery = "SELECT * FROM $this->tableName WHERE id = ?";
            $selectStmt = $this->pdo->prepare($selectQuery);
            $selectStmt->bindValue(1, $id);
            $selectStmt->execute();

            $result = $selectStmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                return $result;
            }

            throw new \Exception("Failed to retrieve the updated record.");
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function delete(int $id): mixed
    {
        // Establish a database connection using PDO
        try {
            // Begin a transaction
            $this->pdo->beginTransaction();

            $query = "DELETE FROM $this->tableName WHERE id = ?";
            $bindings = [$id];

            // Prepare the SQL statement
            $stmt = $this->pdo->prepare($query);

            // Bind values to placeholders
            $stmt->bindValue(1, $id);

            // Execute the SQL statement
            $stmt->execute();

            // Check if a transaction is active before committing
            if ($this->pdo->inTransaction()) {
                // Commit the transaction
                $this->pdo->commit();
            }

            // Return true if the deletion was successful
            return true;
        } catch (PDOException $e) {
            // Roll back the transaction only if it's active
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }
}