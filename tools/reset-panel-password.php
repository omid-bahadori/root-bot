<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config.php';

$username = trim((string) ($argv[1] ?? 'admin'));
$password = (string) ($argv[2] ?? '');
if ($username === '' || $password === '' || strlen($password) < 12) {
    fwrite(STDERR, "Usage: php tools/reset-panel-password.php <username> <password>\n");
    fwrite(STDERR, "Password must contain at least 12 characters.\n");
    exit(2);
}

$statement = $pdo->prepare('UPDATE admin SET password = ? WHERE username = ?');
$statement->execute([password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]), $username]);

if ($statement->rowCount() !== 1) {
    fwrite(STDERR, "Panel user not found: {$username}\n");
    exit(1);
}

fwrite(STDOUT, "Panel password updated for {$username}.\n");
