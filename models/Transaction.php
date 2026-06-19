<?php
class Transaction {
    private $conn;
    private $table = "transactions";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (wallet_code, code_trans, montant, type, frais) 
                  VALUES (:wallet_code, :code_trans, :montant, :type, :frais)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function getAll() {
        $query = "SELECT t.*, w.nom, w.prenom 
                  FROM " . $this->table . " t 
                  JOIN wallets w ON t.wallet_code = w.code 
                  ORDER BY t.date_heure DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}