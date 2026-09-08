Root Bot — Services overview (MVP)

هدف: ارائه‌ی مجموعه‌ای از سرویس‌ها با پنل مدیریت و کیف پول ساده. این مستند کوتاه نحوه‌ی افزودن سرویس و نحوه‌ی استفاده از API محلی را توضیح می‌دهد.

فایل سرویس‌ها
- data/services.json: لیست سرویس‌ها، id، نام، دسته‌بندی، توضیح و نوع قیمت‌گذاری.

APIهای جدید
- api/services.php
  - GET /api/services.php -> لیست سرویس‌های فعال
  - GET /api/services.php?id=repair -> جزئیات سرویس

- api/wallet.php
  - GET /api/wallet.php?action=balance&user=123
  - GET /api/wallet.php?action=topup&user=123 (stub)
  - GET /api/wallet.php?action=pay&user=123 (stub)

پنل مدیریت
- panel/services.php (صفحه‌ی مدیریت سرویس‌ها، هنوز ساده)

تذکرهای فنی
- این scaffold صرفاً پایه است و برای production لازم است:
  - صندوق‌های ذخیره (DB) برای کیف پول و سفارش‌ها
  - احراز هویت و دسترسی پنل
  - webhook پرداخت و پردازش امن
  - لاگ‌گذاری و مانیتورینگ

در صورت تایید، مراحل بعدی:
1) پیاده‌سازی DB برای orders و wallet
2) افزودن UI پنل برای ویرایش قیمت‌ها و محصولات
3) اتصال پرداخت‌های ارزی/دلاری با gatewayهای منتخب
4) حساب کاربری و mini app تلگرام
