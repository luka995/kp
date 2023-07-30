<?php

namespace App\Classes;

/**
 *
 * @author Luka
 */
class Database {
    
    /**
     * @var mysqli
    */
    private $link;
    
    /**
     * @var Database|null
    */
    private static $instance = null;

    /**
     * Database constructor.
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $db
     *
     * @throws \Exception
     */
    private function __construct(string $host, string $user, string $password, string $db) 
    {
        $this->link = new mysqli($host, $user, $password, $db);

        if ($this->link->connect_error) {
            throw new \Exception('Database connection error: ' . $this->link->connect_error);
        }
    }

    /**
     * Get instance of the Database.
     *
     * @return Database
    */
    public static function getInstance(): self 
    {
        if (self::$instance == null) {
            $config = include __DIR__ . '/../../config.php';
            $dbConfig = $config['db'];
            self::$instance = new Database('localhost', $dbConfig['user'], $dbConfig['password'], $dbConfig['name']);
        }
        return self::$instance;
    }
    
    /**
     * Determine types of the values.
     *
     * @param mixed|array $values
     * @return string
    */
    private function determineTypes($values): string 
    {
        $types = '';
        // Ako $values nije niz, pretvaram ga u niz sa jednim elementom
        if (!is_array($values)) {
            $values = [$values];
        }
        foreach ($values as $value) {
            if (is_string($value)) {
                $types .= 's';
            } elseif (is_int($value)) {
                $types .= 'i';
            } elseif (is_bool($value)) {
                $types .= 'b';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }
    
    /**
     * Execute the statement.
     *
     * @param mysqli_stmt $stmt
     * @param mixed $values
     */
    private function executeStatement(mysqli_stmt $stmt, mixed $values): void 
    {
        $types = $this->determineTypes($values);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
    }
    
    /**
     * Check for error in database operation.
     *
     * @param int $id
     * @throws \Exception
    */
    private function checkForError(int $id): void 
    {
        if ($id == 0) {
            // Insert or update failed, throw an exception.
            throw new \Exception('Database operation failed.');
        }
    }

    
    /**
     * Get row by column value.
     *
     * @param string $table
     * @param string $col
     * @param mixed $val
     * @param string $extraWhereSQL
     * @return array
    */
    public function getByCol(string $table, string $col, mixed $val, string $extraWhereSQL = ""): array 
    {
        $sql = "SELECT * FROM $table WHERE $col=?" . (!empty($extraWhereSQL) ? ' AND ' . $extraWhereSQL : '');
        $stmt = $this->link->prepare($sql);
        $this->executeStatement(&$stmt, $val);
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
    /**
     * Get row by ID.
     *
     * @param string $table
     * @param int $id
     * @return array
    */
    public function getById(string $table, int $id): array 
    {
        return $this->getByCol($table, 'id', $id);
    }
    
    /**
     * Insert a new row.
     *
     * @param string $table
     * @param array $data
     * @param string $whereSQL
     * @param string $extraSetSQL
     * @param string $extraWhereSQL
     * @return int
     * @throws \Exception
    */
    public function insert(string $table, array $data, string $whereSQL, string $extraSetSQL = "", string $extraWhereSQL = ""): int 
    {
        $set = implode(', ', array_map(function ($col) { return "$col = ?"; }, array_keys($data)));
        $values = array_values($data);

        $sql = "INSERT INTO $table SET $set " 
                . (!empty($extraSetSQL) ? ', ' . $extraSetSQL : ''). " WHERE $whereSQL" 
                . (!empty($extraWhereSQL) ? ' AND ' . $extraWhereSQL : '');
        $stmt = $this->link->prepare($sql);
        $this->executeStatement($stmt, $values);
        
        $insertId = $this->link->insert_id;
        $this->checkForError($insertId);
    
        return $insertId;
    }

    
    /**
     * Update a row.
     *
     * @param string $table
     * @param array $data
     * @param string $whereSQL
     * @param string $extraSetSQL
     * @param string $extraWhereSQL
     * @return int
     * @throws \Exception
    */
    public function update(string $table, array $data, string $whereSQL, string $extraSetSQL = "", string $extraWhereSQL = ""): int 
    {
        $set = implode(', ', array_map(function ($col) { return "$col = ?"; }, array_keys($data)));
        $values = array_values($data);

        $sql = "UPDATE $table SET $set " 
                . (!empty($extraSetSQL) ? ', ' . $extraSetSQL : '') . " WHERE $whereSQL" 
                . (!empty($extraWhereSQL) ? ' AND ' . $extraWhereSQL : '');
        $stmt = $this->link->prepare($sql);
        $this->executeStatement(&$stmt, $values);
    
        $affectedRows = $stmt->affected_rows;
        $this->checkForError($affectedRows);
    
        return $affectedRows;
    }
    
    /**
     * Delete a row by ID.
     *
     * @param string $table
     * @param int $id
     * @return int
    */
    public function delete(string $table, int $id): int
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->link->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }

    /**
     * Begin a new transaction.
    */
    public function beginTransaction(): void
    {
        $this->link->begin_transaction();
    }

    /**
     * Commit the current transaction.
    */
    public function commit(): void
    {
        $this->link->commit();
    }

    /**
     * Rollback the current transaction.
    */
    public function rollback(): void
    {
        $this->link->rollback();
    }    
}