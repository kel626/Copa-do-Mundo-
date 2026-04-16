<?php
class Selecao {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function readAll() {
        $stmt = $this->conn->prepare("SELECT * FROM selecoes ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nome, $grupo, $titulos) {
        $stmt = $this->conn->prepare("INSERT INTO selecoes (nome, grupo, titulos) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $grupo, $titulos]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM selecoes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}