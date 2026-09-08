<?php

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/../src/CommerceRepository.php';

header('Content-Type: application/json; charset=utf-8');

function commerceResponse(bool $success, string $message, array $data = [], int $code = 200): never
{
    http_response_code($code);
    echo json_encode([
        'status' => $success,
        'message' => $message,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function commerceBody(): array
{
    $raw = file_get_contents('php://input');
    $data = $raw === false ? null : json_decode($raw, true);
    if (!is_array($data)) {
        commerceResponse(false, 'JSON body is required', [], 400);
    }
    return $data;
}

function commerceText(array $data, string $field, int $maxLength = 128): string
{
    $value = $data[$field] ?? null;
    if (!is_string($value) && !is_int($value)) {
        commerceResponse(false, "{$field} is invalid", [], 422);
    }
    $value = trim((string) $value);
    if ($value === '' || strlen($value) > $maxLength) {
        commerceResponse(false, "{$field} is invalid", [], 422);
    }
    return $value;
}

function commerceAmount(array $data): string
{
    $value = $data['amount'] ?? null;
    if (is_int($value)) {
        $value = (string) $value;
    }
    if (!is_string($value) || !preg_match('/^(?:0|[1-9]\d{0,17})(?:\.\d{1,2})?$/', $value)) {
        commerceResponse(false, 'amount is invalid', [], 422);
    }
    if ((float) $value <= 0) {
        commerceResponse(false, 'amount must be positive', [], 422);
    }
    return $value;
}

function commerceRepository(): CommerceRepository
{
    global $pdo;
    if (!$pdo instanceof PDO) {
        commerceResponse(false, 'database unavailable', [], 503);
    }
    return new CommerceRepository($pdo);
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$action = (string) ($_GET['action'] ?? '');

if ($method === 'GET' && $action === 'balance') {
    $userId = commerceText($_GET, 'user');
    commerceResponse(true, 'balance loaded', commerceRepository()->balance($userId));
}

if ($method === 'GET' && $action === 'order') {
    $orderRef = commerceText($_GET, 'ref', 190);
    $order = commerceRepository()->order($orderRef);
    commerceResponse($order !== null, $order === null ? 'order not found' : 'order loaded', $order ?? [], $order === null ? 404 : 200);
}

requireApiToken(getallheaders());

if ($method !== 'POST') {
    commerceResponse(false, 'method not allowed', [], 405);
}

$data = commerceBody();
$repository = commerceRepository();

if ($action === 'topup') {
    $userId = commerceText($data, 'user');
    $amount = commerceAmount($data);
    $currency = commerceText($data + ['currency' => 'IRR'], 'currency', 8);
    $gateway = commerceText($data + ['gateway' => 'manual'], 'gateway', 64);
    commerceResponse(true, 'topup created', $repository->createTopup($userId, $amount, $currency, $gateway), 201);
}

if ($action === 'order') {
    $userId = commerceText($data, 'user');
    $serviceId = commerceText($data, 'service_id');
    $amount = commerceAmount($data);
    $currency = commerceText($data + ['currency' => 'IRR'], 'currency', 8);
    $payload = isset($data['payload']) && is_array($data['payload']) ? $data['payload'] : [];
    commerceResponse(true, 'order created', $repository->createOrder($userId, $serviceId, $amount, $currency, $payload), 201);
}

commerceResponse(false, 'unknown action', [], 400);
