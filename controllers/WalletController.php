<?php
require_once __DIR__ . '/../services/WalletService.php';

class WalletController {
    public function create() {
        $data = [
            'code'       => trim($_POST['code'] ?? ''),
            'nom'        => trim($_POST['nom'] ?? ''),
            'prenom'     => trim($_POST['prenom'] ?? ''),
            'telephone'  => trim($_POST['telephone'] ?? ''),
            'solde'      => floatval($_POST['solde'] ?? 0)
        ];

        $service = new WalletService(null);
        $result = $service->createWallet($data);

        if ($result === "success") {
            header("Location: ../index.php?success=Wallet créé avec succès ✅");
        } else {
            header("Location: ../index.php?error=" . urlencode($result));
        }
        exit;
    }
}