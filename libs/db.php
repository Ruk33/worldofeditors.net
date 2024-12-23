<?php

function get_pdo_connection()
{
    $host       = getenv("DB_HOST");
    $dbname     = getenv("DB_NAME");
    $user       = getenv("DB_USER");
    $password   = getenv("DB_PASSWORD");

    $dsn = "pgsql:host=$host;dbname=$dbname";

    return new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function find(string $table, array $where = [], $limit = 1, PDO $pdo = null)
{
    if ($pdo === null) {
        $pdo = get_pdo_connection();
    }

    $where_clauses = [];
    $params = [];

    if (!empty($where)) {
        foreach ($where as $column => $value) {
            $where_clauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }
    }

    $sql = "SELECT * FROM $table";
    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(' AND ', $where_clauses);
    }
    $sql .= " LIMIT :limit";
    $params[":limit"] = $limit;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function insert(string $table, array $data, PDO $pdo = null)
{
    if ($pdo === null) {
        $pdo = get_pdo_connection();
    }

    $columns = array_keys($data);
    $placeholders = array_map(fn($column) => ":$column", $columns);

    $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $stmt = $pdo->prepare($sql);

    $params = [];
    foreach ($data as $column => $value) {
        $params[":$column"] = $value;
    }

    $stmt->execute($params);

    return $pdo->lastInsertId();
}

function run_query(string $sql, array $params = [], PDO $pdo = null)
{
    if ($pdo === null) {
        $pdo = get_pdo_connection();
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt;
}

// $host       = getenv("DB_HOST");
// $dbname     = getenv("DB_NAME");
// $user       = getenv("DB_USER");
// $password   = getenv("DB_PASSWORD");

// $dsn = "pgsql:host=" . $host . ";dbname=" . $dbname;

// // Create a PDO instance
// $pdo = new PDO($dsn, $user, $password);

// // Set error mode to exceptions
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// // Query the database
// $stmt = $pdo->query("SELECT * FROM your_table");

// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     echo "ID: " . $row['id'] . ", Name: " . $row['name'] . "<br>";
// }
