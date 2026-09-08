<?php
// Simple admin listing for services (requires integrating with panel auth)
require_once __DIR__ . '/../config.php';
$datafile = __DIR__ . '/../data/services.json';
$services = ['services' => []];
if (is_file($datafile)) {
    $services = json_decode(file_get_contents($datafile), true);
}
?>
<!doctype html>
<html>
  <head><meta charset="utf-8"><title>Root Bot - Services</title></head>
  <body>
    <h1>Services</h1>
    <p>This is a lightweight admin view. Integrate with your panel auth to restrict access.</p>
    <table border="1" cellpadding="6">
      <thead><tr><th>id</th><th>name</th><th>category</th><th>pricing</th><th>enabled</th></tr></thead>
      <tbody>
<?php foreach ($services['services'] as $s): ?>
  <tr>
    <td><?= htmlspecialchars($s['id']) ?></td>
    <td><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['category']) ?></td>
    <td><?= htmlspecialchars(json_encode($s['pricing'])) ?></td>
    <td><?= $s['enabled'] ? 'yes' : 'no' ?></td>
  </tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </body>
</html>
