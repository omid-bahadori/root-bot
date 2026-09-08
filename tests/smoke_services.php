<?php
// Basic smoke test for services scaffold
$base = __DIR__ . '/../data/services.json';
if (!is_file($base)) {
    fwrite(STDERR, "services.json missing\n");
    exit(2);
}
$data = json_decode(file_get_contents($base), true);
if (!isset($data['services']) || count($data['services']) === 0) {
    fwrite(STDERR, "no services defined\n");
    exit(2);
}
echo "Services smoke OK (" . count($data['services']) . " services)\n";
exit(0);
