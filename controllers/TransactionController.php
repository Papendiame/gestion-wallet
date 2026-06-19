<?php
require_once __DIR__ . '/../services/WalletService.php';

class TransactionController {
    public function operation() {
        $type      = $_POST['type'] ?? '';
        $telephone = trim($_POST['telephone'] ?? '');
        $montant   = floatval($_POST['montant'] ?? 0);

        $service = new WalletService(null);
        $result = $service->doOperation($type, $telephone, $montant);

        if ($result === "success") {
            header("Location: /gestion-wallet/index.php?success=Opération effectuée avec succès ✅");
        } else {
            header("Location: /gestion-wallet/index.php?error=" . urlencode($result));
        }
        exit;
    }
}
