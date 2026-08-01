# دليل إعداد النظام للإنتاج - Production Setup Guide

هذا الدليل يشرح خطوة بخطوة كيفية إعداد نظام مراقبة الأخطاء، النسخ الاحتياطي، والنشر بدون توقف.

## 📋 المتطلبات المسبقة

- PHP 8.2+
- Composer
- SSH Access للسيرفر الإنتاجي
- حسابات على:
  - Sentry.io (لمراقبة الأخطاء)
  - Slack (للتنبيهات)
  - Google Drive أو AWS S3 (للنسخ الاحتياطي)

---

## 1️⃣ تثبيت الحزم (Package Installation)

```bash
# داخل مجلد المشروع
cd /var/www/almusanada

# تثبيت الحزم الجديدة
php composer.phar install --no-dev --optimize-autoloader

# أو إذا كان Composer متاحاً عالمياً
composer install --no-dev --optimize-autoloader
```

---

## 2️⃣ إعداد Sentry لمراقبة الأخطاء

### الخطوة 1: إنشاء حساب Sentry
1. انتقل إلى https://sentry.io
2. أنشئ مشروع جديد باسم `almusanada`
3. اختر Laravel كمنصة
4. انسخ الـ DSN (Data Source Name)

### الخطوة 2: إضافة الإعدادات
في ملف `.env` على السيرفر الإنتاجي:

```env
SENTRY_LARAVEL_DSN=https://xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@xxxxxxxx.ingest.sentry.io/xxxxxxx
SENTRY_TRACES_SAMPLE_RATE=1.0
```

### الخطوة 3: اختبار الإعداد
```bash
php artisan sentry:test
```

### الخطوة 4: ربط Slack بـ Sentry
1. في لوحة تحكم Sentry، انتقل إلى: Settings → Integrations
2. اختر Slack واتبع خطوات الربط
3. اختر القناة #errors
4. فعل التنبيهات للأخطاء الحرجة (Critical Errors)

---

## 3️⃣ إعداد Slack للتنبيهات المباشرة

### الخطوة 1: إنشاء Slack App
1. انتقل إلى https://api.slack.com/apps
2. أنشئ تطبيق جديد → From scratch
3. اسم التطبيق: `Almusanada Bot`
4. اختر Workspace

### الخطوة 2: تفعيل Incoming Webhooks
1. في sidebar، اذهب إلى: Incoming Webhooks
2. فعل `Activate Incoming Webhooks`
3. اضغط `Add New Webhook to Workspace`
4. اختر القناة (#errors أو #general)
5. انسخ Webhook URL

### الخطوة 3: إضافة الإعدادات
في `.env`:

```env
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL_HERE
SLACK_CHANNEL=#errors
SLACK_BOT_NAME="Almusanada Medical Bot"
```

---

## 4️⃣ إعداد النسخ الاحتياطي (Spatie Laravel-Backup)

### الخيار A: Google Drive (الأسهل)

#### الخطوة 1: إنشاء Google Cloud Project
1. انتقل إلى https://console.cloud.google.com
2. أنشئ مشروع جديد
3. فعل Google Drive API:
   - APIs & Services → Library
   - ابحث عن "Google Drive API"
   - اضغط Enable

#### الخطوة 2: إنشاء Service Account
1. APIs & Services → Credentials
2. Create Credentials → Service Account
3. امنح الأدوار:
   - Google Drive API → Editor
4. أنشئ مفتاح JSON:
   - في Service Account → Keys → Add Key → Create new key
   - اختر JSON وحمّل الملف

#### الخطوة 3: مشاركة مجلد Google Drive
1. أنشئ مجلد جديد في Google Drive (مثلاً: Almusanada Backups)
2. انسخ Folder ID من URL (جزء بعد /folders/)
3. شارك المجلد مع email الـ Service Account (editor)

#### الخطوة 4: رفع credentials
```bash
# على السيرفر
mkdir -p storage/app/google-drive
cp /path/to/downloaded-credentials.json storage/app/google-drive/credentials.json
chmod 600 storage/app/google-drive/credentials.json
```

#### الخطوة 5: إعدادات `.env`
```env
# Backup General
BACKUP_ARCHIVE_PASSWORD=your-very-strong-password-here-min-32-chars
BACKUP_MAIL_TO=admin@almusanada.com
BACKUP_SLACK_WEBHOOK_URL=${SLACK_WEBHOOK_URL}
BACKUP_SLACK_CHANNEL=#backups

# Google Drive
GOOGLE_DRIVE_CREDENTIALS_PATH=storage/app/google-drive/credentials.json
GOOGLE_DRIVE_BACKUP_FOLDER_ID=your-folder-id-here
```

#### الخطوة 6: تحديث `config/backup.php`
```php
'disks' => [
    'local',
    'google-drive', // أضف هذا
],
```

### الخيار B: AWS S3 (للمؤسسات)

#### الخطوة 1: إنشاء S3 Bucket
```bash
aws s3api create-bucket --bucket almusanada-backups --region us-east-1
aws s3api put-bucket-versioning --bucket almusanada-backups --versioning-configuration Status=Enabled
```

#### الخطوة 2: إنشاء IAM User محدود
```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "s3:PutObject",
                "s3:GetObject",
                "s3:DeleteObject"
            ],
            "Resource": "arn:aws:s3:::almusanada-backups/*"
        }
    ]
}
```

#### الخطوة 3: إعدادات `.env`
```env
BACKUP_S3_ACCESS_KEY_ID=your-access-key
BACKUP_S3_SECRET_ACCESS_KEY=your-secret-key
BACKUP_S3_DEFAULT_REGION=us-east-1
BACKUP_S3_BUCKET=almusanada-backups
```

---

## 5️⃣ جدولة النسخ الاحتياطي (Cron)

### الخطوة 1: إعداد Cron على السيرفر
```bash
# فتح crontab
sudo crontab -e

# إضافة هذا السطر (يعمل كل دقيقة)
* * * * * cd /var/www/almusanada && php artisan schedule:run >> /dev/null 2>&1
```

### الخطوة 2: التحقق من الجدولة
```bash
# عرض المهام المجدولة
php artisan schedule:list

# محاكاة التشغيل
php artisan schedule:run --dry-run
```

### الخطوة 3: اختبار النسخ الاحتياطي يدوياً
```bash
# تشغيل نسخة احتياطية يدوية
php artisan backup:run

# التحقق من النسخ
php artisan backup:list

# تنظيف النسخ القديمة
php artisan backup:clean
```

---

## 6️⃣ إعداد النشر بدون توقف (Laravel Envoy)

### الخطوة 1: إعداد SSH Keys
```bash
# على الجهاز المحلي (dev machine)
ssh-keygen -t ed25519 -C "deploy@almusanada"

# نسخ المفتاح العام للسيرفر
ssh-copy-id -i ~/.ssh/id_ed25519.pub user@your-server.com
```

### الخطوة 2: تحديث Envoy.blade.php
افتح `Envoy.blade.php` وعدل المتغيرات:

```blade
@servers(['web' => ['deploy@your-server.com']])

@setup
    $repository = 'git@github.com:yourusername/almusanada.git';
    $releases_dir = '/var/www/almusanada/releases';
    $app_dir = '/var/www/almusanada';
@endsetup
```

### الخطوة 3: إعداد Git Deploy Key
1. على GitHub/GitLab:
   - Settings → Deploy Keys → Add deploy key
   - انسخ المفتاح العام من السيرفر: `cat ~/.ssh/id_ed25519.pub`

2. على السيرفر:
```bash
# اختبار الاتصال
ssh -T git@github.com
```

### الخطوة 4: إعداد المجلدات على السيرفر
```bash
# على السيرفر
sudo mkdir -p /var/www/almusanada/{releases,storage}
sudo chown -R www-data:www-data /var/www/almusanada
sudo chmod -R 755 /var/www/almusanada

# ربط storage
ln -s /var/www/almusanada/storage /var/www/almusanada/current/storage
```

### الخطوة 5: تثبيت Envoy محلياً
```bash
composer global require laravel/envoy

# أو محلياً في المشروع
composer require laravel/envoy --dev
```

### الخطوة 6: النشر
```bash
# من الجهاز المحلي
envoy run deploy

# أو مع branch محدد
envoy run deploy --branch=develop
```

### الخطوة 7: Rollback (في حال فشل النشر)
```bash
envoy run rollback
```

---

## 7️⃣ إعداد Web Server (Nginx)

### ملف الإعداد: `/etc/nginx/sites-available/almusanada`

```nginx
server {
    listen 80;
    server_name almusanada.com www.almusanada.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name almusanada.com www.almusanada.com;

    root /var/www/almusanada/current/public;
    index index.php;

    ssl_certificate /path/to/fullchain.pem;
    ssl_certificate_key /path/to/privkey.pem;

    # Health check endpoint
    location /health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }

    # Laravel Up endpoint
    location /up {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /(\.env|\.git|composer\.(json|lock)) {
        deny all;
    }

    # Optimize static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### تفعيل الإعداد
```bash
sudo ln -s /etc/nginx/sites-available/almusanada /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 8️⃣ إعداد Queue Workers (Supervisor)

### ملف الإعداد: `/etc/supervisor/conf.d/almusanada-worker.conf`

```ini
[program:almusanada-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/almusanada/current/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/almusanada/storage/logs/worker.log
stopwaitsecs=3600
```

### تشغيل Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start almusanada-worker:*
```

---

## 9️⃣ قائمة مراجعة ما قبل الإنتاج (Pre-Launch Checklist)

### ✅ الأمان
- [ ] تغيير `APP_KEY` (`php artisan key:generate`)
- [ ] تعيين `APP_ENV=production`
- [ ] تعيين `APP_DEBUG=false`
- [ ] تغيير كلمة مرور النسخ الاحتياطي (`BACKUP_ARCHIVE_PASSWORD`)
- [ ] تفعيل SSL/HTTPS
- [ ] تأمين ملف `.env` (`chmod 600 .env`)
- [ ] إعداد Rate Limiting

### ✅ الأداء
- [ ] تشغيل `php artisan optimize`
- [ ] تفعيل `config:cache`
- [ ] تفعيل `route:cache`
- [ ] تفعيل `view:cache`
- [ ] تفعيل `event:cache`

### ✅ المراقبة
- [ ] اختبار Sentry (`php artisan sentry:test`)
- [ ] اختبار Slack Webhook
- [ ] اختبار النسخ الاحتياطي (`php artisan backup:run`)
- [ ] اختبار Health Check (`curl https://almusanada.com/up`)

### ✅ الاستعادة
- [ ] اختبار استعادة قاعدة بيانات من نسخة احتياطية
- [ ] تسجيل خطوات الاستعادة في وثيقة
- [ ] تدريب فريق الدعم على الاستعادة

---

## 🔧 استكشاف الأخطاء (Troubleshooting)

### مشكلة: فشل النسخ الاحتياطي
```bash
# عرض تفاصيل الخطأ
php artisan backup:run --verbose

# التحقق من صلاحيات storage
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### مشكلة: Envoy لا يعمل
```bash
# التحقق من SSH
ssh -v user@server.com

# التحقق من صلاحيات Git
git ls-remote git@github.com:your-repo.git
```

### مشكلة: Sentry لا يستقبل أخطاء
```bash
# التحقق من DSN
grep SENTRY .env

# اختبار الاتصال
curl -I https://sentry.io
```

---

## 📞 دعم طوارئ

في حال حدوث كارثة:

1. **فشل النظام بالكامل**:
   ```bash
   # استعادة آخر نسخة احتياطية
   php artisan backup:restore
   ```

2. **فقدان قاعدة البيانات**:
   - نزل آخر نسخة من Google Drive/S3
   - استخرج ملف SQLite
   - انسخ إلى `database/database.sqlite`

3. **الاتصال بفريق التطوير**:
   - Slack: #dev-emergency
   - Email: dev@almusanada.com
   - Phone: +966-XX-XXX-XXXX

---

**آخر تحديث:** 2026-05-29
**الإصدار:** 1.0.0
