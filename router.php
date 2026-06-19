<?php

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'create_wallet') {
    require_once __DIR__ . '/controllers/WalletController.php';
    $controller = new WalletController();
    $controller->create();
} 
elseif ($action === 'operation') {
    require_once __DIR__ . '/controllers/TransactionController.php';
    $controller = new TransactionController();
    $controller->operation();
} else {
    // Si aucune action, on reste sur index.php
    header("Location: index.php");
}