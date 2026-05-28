# Server Setup Guide - Smart-Klontong

## 📋 Checklist Setup Server

Setelah `git push` code ke server, ikuti langkah-langkah berikut:

## Step 1: Update Environment Variables

```bash
# Edit .env di server
nano .env
```

Pastikan setting untuk production:

```env
APP_ENV=production
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=smartklontongonline@gmail.com
MAIL_PASSWORD=zjbsaikxazhqphwy
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=smartklontongonline@gmail.com
MAIL_FROM_NAME="Smart-Klontong"
```

## Step 2: Database Migrations

```bash
# Run migrations jika belum
php artisan migrate --force

# Verify jobs table exists
php artisan tinker
> DB::table('jobs')->count();
> exit
```

## Step 3: Setup Queue Worker

### Option A: Manual (for testing)

```bash
# Jalankan worker (akan berjalan di foreground)
php artisan queue:work --timeout=60
```

### Option B: Supervisor (Recommended - Auto-restart)

**Install Supervisor:**

```bash
sudo apt-get update
sudo apt-get install supervisor
```

**Create config file:**

```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

**Paste ini:**

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/smart-klontong/artisan queue:work --timeout=60 --memory=128
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/smart-klontong/storage/logs/worker.log
user=www-data
```

**Start Supervisor:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

**Check status:**

```bash
sudo supervisorctl status
```

## Step 4: Setup Scheduler Cron

```bash
# Edit crontab
crontab -e
```

**Add this line:**

```cron
* * * * * cd /var/www/smart-klontong && php artisan schedule:run >> storage/logs/scheduler.log 2>&1
```

**Verify cron:**

```bash
crontab -l
```

## Step 5: Verify Setup

```bash
# Check queue configuration
php artisan config:show queue

# Check if jobs table is ready
php artisan tinker
> App\Models\User::first()->notify(new \App\Notifications\StokKritisNotification(collect([])));
> exit

# Check jobs queue
php artisan queue:failed
```

## Step 6: Monitor & Maintain

```bash
# View queue status
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry <id>

# Check scheduler logs
tail -f storage/logs/scheduler.log

# Check worker logs
tail -f storage/logs/worker.log
```

## 🔄 Automated Deployment Script (Optional)

Create `deploy.sh`:

```bash
#!/bin/bash

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue worker
sudo supervisorctl restart laravel-worker:*

echo "✓ Deployment complete!"
```

Usage:

```bash
chmod +x deploy.sh
./deploy.sh
```

## ✅ Verification Tests

### Test 1: Queue Worker Running

```bash
ps aux | grep "queue:work"
```

Expected: See `php artisan queue:work` process

### Test 2: Scheduler Running

```bash
ps aux | grep "cron"
```

Expected: See `cron` daemon

### Test 3: Send Test Notification

```bash
php artisan tinker
> $owner = App\Models\User::where('role', 'owner')->first();
> $owner->notify(new \App\Notifications\StokKritisNotification(collect([])));
> exit

# Check if job was queued
php artisan queue:work --max-jobs=1

# Check Gmail to see if email arrived
```

### Test 4: Scheduled Commands

```bash
# Test stok check
php artisan stok:cek-kritis

# Test hutang check
php artisan hutang:cek-jatuh-tempo

# Expected: Emails should be sent
```

## 🆘 Troubleshooting

### Queue Worker not running

```bash
# Check supervisor status
sudo supervisorctl status

# Restart if needed
sudo supervisorctl restart laravel-worker:*

# Check error logs
sudo tail -50 /var/www/smart-klontong/storage/logs/worker.log
```

### Jobs stuck in queue

```bash
# List pending jobs
php artisan tinker
> DB::table('jobs')->count();

# Clear failed jobs
php artisan queue:flush

# Restart worker
sudo supervisorctl restart laravel-worker:*
```

### Emails not sending

```bash
# Check mail configuration
php artisan config:show mail

# Test email
php artisan tinker
> Mail::raw('Test email', fn($m) => $m->to('test@example.com')->subject('Test'));

# Check Gmail sent folder
```

### Scheduler not running

```bash
# Check cron
crontab -l

# Check logs
tail -50 /var/www/smart-klontong/storage/logs/scheduler.log

# Test scheduler manually
php artisan schedule:run

# View schedule list
php artisan schedule:list
```

## 📊 Performance Tips

1. **Queue Worker Memory**
    - Set `--memory=128` atau lebih tinggi jika needed

2. **Multiple Workers** (untuk high volume)

    ```ini
    [program:laravel-worker]
    numprocs=4  # Start 4 workers
    ```

3. **Failed Jobs Handler**

    ```bash
    # Create listener untuk handle failed jobs
    php artisan make:listener --queued FailedJobHandler
    ```

4. **Monitor Memory Usage**
    ```bash
    sudo supervisorctl tail laravel-worker stdout
    ```

## ✨ Expected Behavior on Server

| Situation                      | Behavior                                |
| ------------------------------ | --------------------------------------- |
| New Stok Kritis di Transaksi   | Email instantly (sync notification)     |
| Scheduled Stok Check (07:30)   | Job queued → Worker sends email         |
| Scheduled Hutang Check (08:00) | Job queued → Worker sends email         |
| Email Error                    | Job retry 3x, then moved to failed_jobs |

---

**Ready to push to production!** 🚀
