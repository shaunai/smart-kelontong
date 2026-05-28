# Git Push Workflow - Smart-Klontong

## 🚀 Langkah-Langkah Deploy

### Di Local Development

#### 1️⃣ **Verify Setup Lokal**

```bash
# Test commands berjalan
php artisan stok:cek-kritis
php artisan hutang:cek-jatuh-tempo
```

Expected: ✅ Notifications terkirim dengan verbose output

#### 2️⃣ **Verify .env Configuration**

```bash
# Harus: QUEUE_CONNECTION=sync
cat .env | grep QUEUE_CONNECTION
```

Expected: `QUEUE_CONNECTION=sync`

#### 3️⃣ **Check Git Status**

```bash
git status
```

Expected changes:

- ✅ Modified: `.env` (QUEUE_CONNECTION=sync)
- ✅ Modified: `app/Notifications/StokKritisNotification.php` (added ShouldQueue)
- ✅ Modified: `app/Notifications/HutangJatuhTempoNotification.php` (added ShouldQueue)
- ✅ Modified: `app/Console/Commands/CekStokKritis.php` (added logging)
- ✅ Modified: `app/Console/Commands/CekHutangJatuhTempo.php` (added logging)
- ✅ Added: `SCHEDULED_EMAIL_FIX.md`
- ✅ Added: `SERVER_SETUP.md`

#### 4️⃣ **Commit Changes**

```bash
git add .

git commit -m "fix(notifications): implement ShouldQueue for dual environment setup

- Restored ShouldQueue in StokKritisNotification for server compatibility
- Added ShouldQueue in HutangJatuhTempoNotification for consistency
- Added verbose logging and error handling to both commands
- Set QUEUE_CONNECTION=sync for local development (sync execution)
- Server will use QUEUE_CONNECTION=database with queue worker
- Added SERVER_SETUP.md with complete deployment guide"
```

#### 5️⃣ **Push ke Server**

```bash
git push origin main
```

### Di Server

#### 1️⃣ **Pull Latest Code**

```bash
cd /var/www/smart-klontong
git pull origin main
```

#### 2️⃣ **Update Environment**

```bash
# Change QUEUE_CONNECTION untuk server
nano .env
```

Change from:

```env
QUEUE_CONNECTION=sync
```

To:

```env
QUEUE_CONNECTION=database
```

Save dan exit (Ctrl+X, Y, Enter)

#### 3️⃣ **Setup Database (if first time)**

```bash
php artisan migrate --force
```

#### 4️⃣ **Setup Queue Worker with Supervisor**

Follow [SERVER_SETUP.md](./SERVER_SETUP.md#step-3-setup-queue-worker) untuk setup Supervisor

#### 5️⃣ **Setup Scheduler Cron**

Follow [SERVER_SETUP.md](./SERVER_SETUP.md#step-4-setup-scheduler-cron) untuk setup cron

#### 6️⃣ **Verify Installation**

```bash
# Check queue config
php artisan config:show queue

# Check worker status
sudo supervisorctl status

# Check cron
crontab -l

# View logs
tail -20 storage/logs/scheduler.log
tail -20 storage/logs/worker.log
```

## 📊 Configuration Comparison

| Aspect                | Local             | Server           |
| --------------------- | ----------------- | ---------------- |
| **File**              | `.env`            | `.env`           |
| **QUEUE_CONNECTION**  | `sync`            | `database`       |
| **Behavior**          | Instant execution | Background queue |
| **Notification Type** | Synchronous       | Asynchronous     |
| **Worker**            | ❌ Not needed     | ✅ Running       |
| **Scheduler**         | ⚠️ Optional       | ✅ Required      |

## 🧪 Testing After Deployment

### Test 1: Check Queue Worker

```bash
# On server
ps aux | grep "queue:work"
```

### Test 2: Send Test Notification

```bash
# On server
php artisan tinker
> App\Models\User::first()->notify(new \App\Notifications\StokKritisNotification(collect([])));
> exit

# Wait a moment, then check Gmail
```

### Test 3: Run Scheduled Commands

```bash
# On server
php artisan stok:cek-kritis
php artisan hutang:cek-jatuh-tempo

# Check Gmail for emails
```

### Test 4: Monitor Queue

```bash
# Check pending jobs
php artisan queue:monitor

# Check failed jobs
php artisan queue:failed
```

## ⚠️ Common Issues

### Issue: Jobs not processing

```bash
# Check if worker is running
sudo supervisorctl status laravel-worker

# If down, restart
sudo supervisorctl restart laravel-worker:*
```

### Issue: Emails in queue but not sending

```bash
# Check mail configuration
php artisan config:show mail

# Verify SMTP credentials in .env
cat .env | grep MAIL
```

### Issue: Commands not running on schedule

```bash
# Check if cron is running
ps aux | grep cron

# Check crontab
crontab -l

# Check scheduler logs
tail -50 storage/logs/scheduler.log
```

## ✅ Deployment Checklist

- [ ] All changes committed locally
- [ ] `git push` successful
- [ ] `git pull` on server successful
- [ ] `.env` updated with `QUEUE_CONNECTION=database`
- [ ] Supervisor config created
- [ ] `sudo supervisorctl start laravel-worker:*` successful
- [ ] Cron job added to `crontab -e`
- [ ] Test notification sent and received
- [ ] Queue worker processing jobs
- [ ] Scheduler running on schedule

## 🎯 Final Verification

After everything is setup, verify with:

```bash
# 1. Queue configuration
php artisan config:show queue
# Expected: queue.default = database

# 2. Queue worker
sudo supervisorctl status
# Expected: laravel-worker:* RUNNING

# 3. Scheduled jobs
php artisan schedule:list
# Expected: Two commands scheduled at 07:30 and 08:00

# 4. Test notification
php artisan tinker
> App\Models\Debt::count() > 0  // Should have debts
> App\Models\Product::withSum('batches', 'stock')->get()->filter(fn($p) => ($p->batches_sum_stock ?? 0) <= 5)->count() > 0  // Critical stock
> exit

# 5. Monitor logs
tail -f storage/logs/worker.log
tail -f storage/logs/scheduler.log
```

---

**All set! 🚀 System ready for production!**
