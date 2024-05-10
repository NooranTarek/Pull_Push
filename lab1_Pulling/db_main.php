<?php

class Database {
    private $host;
    private $user;
    private $password;
    private $dbname;
    private $port;
    private $pdo;

    public function __construct($host, $user, $password, $dbname, $port) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
    }

    public function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};port={$this->port}";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "<h3 style='color:red'>{$e->getMessage()}</h3>";
            return false;
        }
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $stmt->rowCount();
    }

    public function select($table) {
        $sql = "SELECT * FROM {$table}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $id, $data) {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "{$key} = ?, ";
        }
        $set = rtrim($set, ', ');

        $sql = "UPDATE {$table} SET {$set} WHERE id = ?";
        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $stmt->rowCount();
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }
}

?>
