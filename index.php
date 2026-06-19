<?php
require_once 'config/database.php';
require_once 'models/Transaction.php';

$db = new Database();
$conn = $db->getConnection();
$transactionModel = new Transaction($conn);
$transactions = $transactionModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 Gestion de Wallet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .section {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }
        h1 { font-size: 2.2em; margin-bottom: 10px; }
        h2 { color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 8px; }
        
        form {
            display: grid;
            gap: 12px;
        }
        input, select, button {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52,152,219,0.3);
        }
        button {
            background: #27ae60;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #219653;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            color: #2c3e50;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .info {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>💰 Mon Wallet - Gestion</h1>
            <p>Application de gestion de portefeuille</p>
        </header>

        <div class="section">
            <?php if(isset($_GET['success'])): ?>
                <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Création Wallet -->
        <div class="section">
            <h2>1. Créer un Nouveau Wallet</h2>
            <form method="POST" action="router.php?action=create_wallet">
                <input type="text" name="code" placeholder="Code Wallet (ex: WALLET001)" required>
                <input type="text" name="nom" placeholder="Nom du titulaire" required>
                <input type="text" name="prenom" placeholder="Prénom du titulaire" required>
                <input type="tel" name="telephone" 
                       pattern="^(77|76|70|78|71)[0-9]{7}$" 
                       placeholder="Téléphone (ex: 776123456)" 
                       title="Doit commencer par 77,76,70,78 ou 71 et contenir 9 chiffres" 
                       maxlength="9" required>
                <input type="number" name="solde" placeholder="Solde initial (CFA)" value="0" min="0" step="0.01">
                <button type="submit">✅ Créer le Wallet</button>
            </form>
            <p class="info">Le numéro doit contenir exactement 9 chiffres et commencer par 70, 71, 76, 77 ou 78.</p>
        </div>

        <!-- Dépôt / Retrait -->
        <div class="section">
            <h2>2. Effectuer une Opération</h2>
            <form method="POST" action="router.php?action=operation">
                <select name="type" required>
                    <option value="DEPOT">💰 Dépôt</option>
                    <option value="RETRAIT">💸 Retrait</option>
                </select>
                <input type="tel" name="telephone" 
                       pattern="^(77|76|70|78|71)[0-9]{7}$" 
                       placeholder="Numéro de téléphone du wallet" 
                       maxlength="9" required>
                <input type="number" name="montant" placeholder="Montant (CFA)" step="0.01" min="1" required>
                <button type="submit">Valider l'Opération</button>
            </form>
        </div>

        <!-- Historique -->
        <div class="section">
            <h2>3. Historique des Transactions</h2>
            <?php if(empty($transactions)): ?>
                <p>Aucune transaction pour le moment.</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Date & Heure</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                </tr>
                <?php foreach($transactions as $t): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($t['date_heure'])) ?></td>
                    <td><?= htmlspecialchars($t['nom'] . ' ' . $t['prenom']) ?></td>
                    <td><strong><?= $t['type'] === 'DEPOT' ? '💰 Dépôt' : '💸 Retrait' ?></strong></td>
                    <td><strong><?= number_format($t['montant'], 2) ?> CFA</strong></td>
                    <td><?= number_format($t['frais'], 2) ?> CFA</td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>