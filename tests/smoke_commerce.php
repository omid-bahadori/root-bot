<?php

$source = file_get_contents(__DIR__ . '/../src/CommerceRepository.php');
if ($source === false || strpos($source, 'rootbot_wallets') === false || strpos($source, 'prepare(') === false) {
    fwrite(STDERR, "commerce repository contract missing\n");
    exit(2);
}

$endpoint = file_get_contents(__DIR__ . '/../api/commerce.php');
if ($endpoint === false || strpos($endpoint, "requireApiToken") === false || strpos($endpoint, "REQUEST_METHOD") === false) {
    fwrite(STDERR, "commerce endpoint contract missing\n");
    exit(2);
}

echo "Commerce smoke OK\n";
