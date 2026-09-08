<?php
header('Content-Type: application/json; charset=utf-8');

// Lightweight wallet stub: no persistent DB here. For production, replace with DB-backed wallet.
$action = $_GET['action'] ?? 'balance';
$user = $_GET['user'] ?? null;
if (!$user) {
    http_response_code(400);
    echo json_encode(['error' => 'user parameter required']);
    exit;
}

// In this scaffold, return a dummy balance and placeholder actions.
switch ($action) {
    case 'balance':
        echo json_encode(['user' => $user, 'balance' => 0.0, 'currency' => 'IRR']);
        break;
    case 'topup':
        // production: validate payment webhook and credit user
        echo json_encode(['user' => $user, 'status' => 'ok', 'message' => 'topup recorded (stub)']);
        break;
    case 'pay':
        // production: debit user and create order
        echo json_encode(['user' => $user, 'status' => 'ok', 'message' => 'payment recorded (stub)']);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'unknown action']);
}
