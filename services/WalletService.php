<?php
// Inclusion correcte de tous les fichiers nécessaires
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Transaction.php';

class WalletService {
    private $walletModel;
    private $transactionModel;

    public function createWallet($data) {
        // Validation téléphone
        if (!preg_match("/^(77|76|70|78|71)[0-9]{7}$/", $data['telephone'])) {
            return "Le numéro doit contenir exactement 9 chiffres et commencer par 70, 71, 76, 77 ou 78.";
        }

        if (empty($data['telephone']) || empty($data['nom']) || empty($data['prenom']) || empty($data['code'])) {
            return "Tous les champs obligatoires doivent être remplis.";
        }
        if ($data['solde'] < 0) {
            return "Le solde ne peut pas être négatif.";
        }

        $db = (new Database())->getConnection();
        $wallet = new Wallet($db);
        
        if ($wallet->findByTelephone($data['telephone'])) {
            return "Ce numéro de téléphone existe déjà !";
        }

        $data['solde'] = $data['solde'] ?? 0;
        if ($wallet->create($data)) {
            return "success";
        }
        return "Erreur lors de la création du wallet.";
    }

    public function doOperation($type, $telephone, $montant) {
        $db = (new Database())->getConnection();
        $walletModel = new Wallet($db);
        $wallet = $walletModel->findByTelephone($telephone);

        if (!$wallet) {
            return "Aucun wallet trouvé avec ce numéro de téléphone.";
        }

        $montant = floatval($montant);
        if ($montant <= 0) {
            return "Le montant doit être positif.";
        }

        $nouveauSolde = $wallet['solde'];
        $frais = 0;

        if ($type === 'DEPOT') {
            $nouveauSolde += $montant;
        } else { // RETRAIT
            $frais = min($montant * 0.01, 5000);
            $total = $montant + $frais;
            
            if ($total > $wallet['solde']) {
                return "Solde insuffisant (montant + frais).";
            }
            $nouveauSolde -= $total;
        }

        $walletModel->updateSolde($wallet['code'], $nouveauSolde);

        $transData = [
            'wallet_code' => $wallet['code'],
            'code_trans'  => 'TRANS-' . strtoupper(uniqid()),
            'montant'     => $montant,
            'type'        => $type,
            'frais'       => $frais
        ];

        $transactionModel = new Transaction($db);
        $transactionModel->create($transData);

        return "success";
    }
}
