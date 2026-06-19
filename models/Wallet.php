<?php
class Wallet {
    private $conn;
    private $table = "wallets";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (code, nom, prenom, telephone, solde) 
                  VALUES (:code, :nom, :prenom, :telephone, :solde)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function findByTelephone($telephone) {
        $query = "SELECT * FROM " . $this->table . " WHERE telephone = :telephone";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['telephone' => $telephone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSolde($code, $nouveauSolde) {
        $query = "UPDATE " . $this->table . " SET solde = :solde WHERE code = :code";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['solde' => $nouveauSolde, 'code' => $code]);
    }
}