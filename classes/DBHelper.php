<?php

class DBHelper {
    private static $connection = null;

    /**
     * Get the singleton database connection.
     *
     * @return mysqli
     * @throws Exception
     */
    public static function getConnection() {
        if (self::$connection === null) {
            $env = parse_ini_file(__DIR__ . "/../.env");
            if ($env === false) {
                throw new Exception("Environment file not found");
            }

            $username = $env["DB_USERNAME"];
            $password = $env["DB_PASSWORD"];
            $database = $env["DB_DATABASE"];
            $host = $env["DB_HOST"];
            
            if(!$username || !$password || !$database || !$host) {
                throw new Exception(".env fiile not set");
            }
            self::$connection = new mysqli($host, $username, $password, $database);

            if (self::$connection->connect_error) {
                throw new Exception("Database connection error: " . self::$connection->connect_error);
            }

            // Ensure UTF-8 encoding
            self::$connection->set_charset("utf8mb4");
        }

        return self::$connection;
    }

    /**
     * Execute a prepared query.
     *
     * @param string $query The SQL query with placeholders.
     * @param array $params Parameters to bind (types and values).
     * @return mysqli_result|bool Result object or true for non-SELECT queries.
     * @throws Exception
     */
    public static function executeQuery($query, $params = []) {
        $conn = self::getConnection();
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        // Bind parameters if provided
        if (!empty($params)) {
            $types = self::getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }

        // Return result for SELECT, or true for other operations
        return $stmt->get_result() ?: true;
    }

    /**
     * Execute a stored procedure.
     *
     * @param string $procedureName Name of the stored procedure.
     * @param array $params Parameters to pass to the procedure.
     * @return mysqli_result|bool Result object or true for non-SELECT operations.
     * @throws Exception
     */
    public static function executeProcedure($procedureName, $params = []) {
        $placeholders = implode(",", array_fill(0, count($params), "?"));
        $query = "CALL $procedureName($placeholders)";

        return self::executeQuery($query, $params);
    }

    /**
     * Call a database function.
     *
     * @param string $functionName Name of the function.
     * @param array $params Parameters to pass to the function.
     * @return mixed Result of the function.
     * @throws Exception
     */
    public static function callFunction($functionName, $params = []) {
        $placeholders = implode(",", array_fill(0, count($params), "?"));
        $query = "SELECT $functionName($placeholders)";

        return self::executeQuery($query, $params)->fetch_row()[0];
    }

    /**
     * Start a transaction.
     *
     * @throws Exception
     */
    public static function beginTransaction() {
        $conn = self::getConnection();
        if (!$conn->begin_transaction()) {
            throw new Exception("Failed to start transaction: " . $conn->error);
        }
    }

    /**
     * Commit a transaction.
     *
     * @throws Exception
     */
    public static function commitTransaction() {
        $conn = self::getConnection();
        if (!$conn->commit()) {
            throw new Exception("Failed to commit transaction: " . $conn->error);
        }
    }

    /**
     * Roll back a transaction.
     *
     * @throws Exception
     */
    public static function rollbackTransaction() {
        $conn = self::getConnection();
        if (!$conn->rollback()) {
            throw new Exception("Failed to roll back transaction: " . $conn->error);
        }
    }

    /**
     * Close the database connection.
     */
    public static function closeConnection() {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }

    /**
     * Infer parameter types for prepared statements.
     *
     * @param array $params Array of parameters.
     * @return string String of parameter types.
     */
    private static function getParamTypes($params) {
        $types = "";
        foreach ($params as $param) {
            $types .= is_int($param) ? "i" : (is_double($param) ? "d" : "s");
        }
        return $types;
    }
}
