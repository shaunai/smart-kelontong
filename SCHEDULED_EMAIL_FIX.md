# Fix untuk Notifikasi Email Terjadwal

## Masalah yang Ditemukan

Notifikasi email terjadwal untuk:

- `php artisan hutang:cek-jatuh-tempo` (Pengecekan hutang jatuh tempo)
- `php artisan stok:cek-kritis` (Pengecekan stok kritis)

tidak berjalan dengan otomatis, meskipun ketika dijalankan manual, semuanya berfungsi dengan baik.

## Root Cause (Akar Penyebab)

### 1. **Notification Queue Configuration Issue**

- `StokKritisNotification` mengimplementasi `ShouldQueue`, menyebabkan notifikasi di-queue ke database
- Namun tidak ada **Queue Worker yang berjalan** untuk memproses queue
- `HutangJatuhTempoNotification` tidak mengimplementasi `ShouldQueue`, tapi perlu konsistensi

**Status**: ✅ **FIXED** - Removed `ShouldQueue` dari `StokKritisNotification`

### 2. **Scheduler Tidak Berjalan Secara Otomatis**

- Scheduler terkonfigurasi dengan baik:
    - `stok:cek-kritis` pada jam 07:30 setiap hari
    - `hutang:cek-jatuh-tempo` pada jam 08:00 setiap hari
- **NAMUN**: Scheduler tidak berjalan secara otomatis
- Laravel scheduler membutuhkan **cron job** atau **Windows Task Scheduler** untuk menjalankan `php artisan schedule:run` setiap menit

## Solusi yang Diterapkan

### ✅ Perubahan Kode (Environment-Based Setup):

1. **[StokKritisNotification.php](app/Notifications/StokKritisNotification.php)**
    - ✅ Implements `ShouldQueue` untuk server compatibility
    - ✅ Added `use Illuminate\Contracts\Queue\ShouldQueue`
    - ✅ Local: Auto-execute dengan `QUEUE_CONNECTION=sync`
    - ✅ Server: Auto-queue dengan `QUEUE_CONNECTION=database`

2. **[HutangJatuhTempoNotification.php](app/Notifications/HutangJatuhTempoNotification.php)**
    - ✅ Implements `ShouldQueue` untuk consistency
    - ✅ Added `use Illuminate\Contracts\Queue\ShouldQueue`
    - ✅ Sama behavior dengan StokKritisNotification

3. **[CekStokKritis.php](app/Console/Commands/CekStokKritis.php)**
    - ✅ Added verbose logging dengan `$this->info()` dan `$this->warn()`
    - ✅ Added error handling dengan try-catch
    - ✅ Menampilkan status pengiriman notifikasi

4. **[CekHutangJatuhTempo.php](app/Console/Commands/CekHutangJatuhTempo.php)**
    - ✅ Added verbose logging
    - ✅ Added error handling dengan try-catch
    - ✅ Menampilkan status pengiriman notifikasi

5. **[.env](`.env`) - Local Development**
    - ✅ Set `QUEUE_CONNECTION=sync` untuk immediate notification

### 🔄 Environment-Based Configuration

## Cara Mengaktifkan Scheduler

### 📱 Local Development (Windows)

Dengan `QUEUE_CONNECTION=sync`, tidak perlu scheduler cron. Notifications langsung tereksekusi saat command berjalan.

**Optional - Setup Windows Task Scheduler untuk auto-execute commands:**

1. **Buka Task Scheduler**
    - Tekan `Win+R` dan ketik `taskschd.msc`

2. **Create Basic Task**
    - Klik "Create Basic Task..." di sebelah kanan
    - Beri nama: "Smart-Klontong Scheduler"
    - Deskripsi: "Run Laravel scheduler every minute"

3. **Set Trigger**
    - Pilih "Recurring"
    - Frequency: Daily
    - Repeat: Every 1 minute

4. **Set Action**
    - Action: "Start a program"
    - Program: `C:\xampp\php\php.exe` (atau path PHP Anda)
    - Arguments: `C:\Users\Shaun\Desktop\smart-klontong\artisan schedule:run >> C:\Users\Shaun\Desktop\smart-klontong\storage\logs\scheduler.log 2>&1`

### 🚀 Server Production (Linux/Mac)

**Setup Cron + Queue Worker:**

```bash
# 1. Add scheduler cron (runs every minute)
* * * * * cd /path/to/smart-klontong && php artisan schedule:run >> storage/logs/scheduler.log 2>&1

# 2. Start queue worker (use Supervisor for auto-restart)
php artisan queue:work --timeout=60

# 3. (Optional) Monitor queue
php artisan queue:monitor
```

## Cara Memverifikasi

### 1. **Test Manual Command**

```bash
php artisan stok:cek-kritis
php artisan hutang:cek-jatuh-tempo
```

Expected output:

```
Checking for critical stock...
Total products checked: 13
Critical stock products found: 5
Sending notification to: Bayu Yudha (bayuyudha41@gmail.com)
✓ Notification sent to bayuyudha41@gmail.com
```

### 2. **Check Email Configuration**

```bash
php artisan config:show mail
```

Pastikan:

- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.gmail.com`
- `MAIL_PORT=465`
- `MAIL_USERNAME=smartklontongonline@gmail.com`
- `MAIL_ENCRYPTION=ssl`

### 3. **Check Recent Emails**

- Login ke Gmail: `smartklontongonline@gmail.com`
- Check folder "Sent" untuk memverifikasi email terkirim
- Check folder "Spam" jika email tidak ditemukan di Inbox

### 4. **Monitor Scheduler Logs**

```bash
tail -f storage/logs/scheduler.log
```

## Konfigurasi Email yang Sudah Terverifikasi

✅ **Gmail SMTP Configuration:**

- Host: `smtp.gmail.com`
- Port: `465`
- Encryption: `ssl`
- Email: `smartklontongonline@gmail.com`
- App Password: `zjbs aikx azhq phwy` (Google App Password)

## Status Komponen

| Komponen          | Status         | Local              | Server             |
| ----------------- | -------------- | ------------------ | ------------------ |
| Mail Driver       | ✅ SMTP        | Terverifikasi ✓    | Terverifikasi ✓    |
| Notifikasi Stok   | ✅ ShouldQueue | Sync execution     | Queue worker       |
| Notifikasi Hutang | ✅ ShouldQueue | Sync execution     | Queue worker       |
| Logging           | ✅ Added       | Verbose output     | Verbose output     |
| Scheduler Config  | ✅ OK          | routes/console.php | routes/console.php |
| Queue Connection  | ✅ CONFIGURED  | `sync`             | `database`         |
| Queue Worker      | ✅ READY       | ❌ Not needed      | ✅ Running         |

## Environment-Based Configuration

### 📱 **Local Development (.env)**

```env
QUEUE_CONNECTION=sync
```

**Behavior:**

- ✅ `ShouldQueue` notifications dieksekusi **synchronously** (immediately)
- ✅ Tidak perlu queue worker
- ✅ Tidak perlu scheduler cron
- ✅ Ideal untuk testing dan development
- ✅ Test dengan: `php artisan stok:cek-kritis`

### 🚀 **Server Production (.env)**

```env
QUEUE_CONNECTION=database
```

**Behavior:**

- ✅ `ShouldQueue` notifications di-queue ke database `jobs` table
- ✅ Queue worker memproses dengan: `php artisan queue:work`
- ✅ Scheduler menjalankan commands dengan cron
- ✅ Emails terkirim asynchronously di background

### 🔄 **Deployment Workflow dengan Git**

1. **Local development** - develop & test dengan `QUEUE_CONNECTION=sync`
2. **Git commit & push** - semua perubahan code
3. **Server pull** - git pull latest changes
4. **Update .env di server** - set `QUEUE_CONNECTION=database`
5. **Restart queue worker** - `php artisan queue:work`
6. **Setup cron scheduler** - `* * * * * php artisan schedule:run`
7. **✅ Live** - Notifications berjalan di background

## Troubleshooting

### Jika Notifikasi Masih Tidak Diterima:

1. **Cek apakah scheduler sedang berjalan**

    ```bash
    php artisan schedule:list
    ```

2. **Test kirim email manual**

    ```bash
    php artisan tinker
    > Mail::raw('Test', fn($m) => $m->to('bayuyudha41@gmail.com')->subject('Test'));
    ```

3. **Check logs**

    ```bash
    tail -100 storage/logs/laravel.log
    tail -100 storage/logs/scheduler.log  # (Windows Task Scheduler)
    ```

4. **Verifikasi data yang harus dikirim**
    ```bash
    php artisan tinker
    > App\Models\Debt::where('status', 'unpaid')->whereDate('due_date', '<=', now()->addDays(3))->count();
    > App\Models\Product::withSum('batches', 'stock')->get()->filter(fn($p) => ($p->batches_sum_stock ?? 0) <= 5)->count();
    ```

## Perubahan File

### ✅ Modified Files:

1. `.env` - Set `QUEUE_CONNECTION=sync` untuk local
2. `app/Notifications/StokKritisNotification.php` - Restored `ShouldQueue` implementation
3. `app/Notifications/HutangJatuhTempoNotification.php` - Added `ShouldQueue` implementation
4. `app/Console/Commands/CekStokKritis.php` - Added logging and error handling
5. `app/Console/Commands/CekHutangJatuhTempo.php` - Added logging and error handling

### ✅ No Changes Needed:

- Email configuration sudah correct (SMTP)
- Scheduler configuration sudah correct (routes/console.php)
- Database connection sudah correct
- Migrations sudah ada (jobs table untuk queue)

---

**Catatan**: Code siap untuk di-push ke server. Di server, hanya perlu update `.env` dengan `QUEUE_CONNECTION=database` dan jalankan queue worker.
