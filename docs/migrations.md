Migrations added

فایل‌های مهاجرت در db/migrations/*.php قرار می‌گیرند. هر فایل باید `return function (PDO $pdo, $schema) { ... };` را بازگرداند و عملیات DDL لازم را اجرا کند.

برای اجرای migrationها در نصب فعلی، از کد زیر استفاده می‌شود (نمونه):

```php
$pdo = new PDO(...);
$schema = new Schema($pdo);
$schema->runMigrations(__DIR__ . '/db/migrations');
```

جداول جدید ایجادشده:
- rootbot_wallets
- rootbot_orders
- rootbot_payments

نکته: این migrationها idempotent هستند و فقط یک بار اجرا می‌شوند. قبل از اجرای مستقیم در production از backup استفاده کنید.
