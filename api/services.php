<?php
header('Content-Type: application/json; charset=utf-8');

$base = __DIR__ . '/../data/services.json';
if (!is_file($base)) {
    http_response_code(500);
    echo json_encode(['error' => 'services data not found']);
    exit;
}

$raw = file_get_contents($base);
$data = json_decode($raw, true);
if ($data === null) {
    http_response_code(500);
    echo json_encode(['error' => 'invalid services data']);
    exit;
}

$query = $_GET['id'] ?? null;
if ($query) {
    foreach ($data['services'] as $s) {
        if ($s['id'] === $query) {
            echo json_encode($s);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['error' => 'service not found']);
    exit;
}

// list enabled services
$out = array_values(array_filter($data['services'], function ($s) { return !empty($s['enabled']); }));
echo json_encode(['services' => $out]);
