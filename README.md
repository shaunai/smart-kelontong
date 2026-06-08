<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🏪 Tentang Smart Klontong

**Smart Klontong** hadir sebagai solusi transformasi digital sistem operasional toko kelontong. Aplikasi berbasis web ini dirancang secara khusus untuk membantu toko berskala ultramikro (Umi) mengatasi tantangan operasional harian agar dapat berkembang dan naik kelas menjadi Usaha Mikro, Kecil, dan Menengah (UMKM) yang terstruktur. 

Seringkali, toko ultramikro kesulitan dalam melacak arus kas, memantau barang yang hampir habis, dan menagih utang pelanggan. Smart Klontong mengurai permasalahan tersebut dengan menyediakan antarmuka operasional yang terotomatisasi, sehingga pemilik toko dapat berfokus pada pengembangan bisnis daripada tenggelam dalam pencatatan manual.

Dikembangkan menggunakan pendekatan *Rapid Application Development* (RAD), aplikasi ini bersifat dinamis dan dirancang untuk dapat diakses secara optimal sebagai *Progressive Web App* (PWA), memastikan aksesibilitas yang mudah langsung dari perangkat pemilik toko.

### ✨ Fitur Utama

Aplikasi ini berfokus pada penyelesaian *pain points* utama dalam manajemen toko kelontong melalui beberapa modul kunci:

*   **Manajemen Stok Kritis:** Sistem pemantauan inventaris yang memberikan peringatan otomatis (notifikasi) ketika jumlah barang hampir habis, memastikan toko tidak pernah kehabisan produk terlaris.
*   **Sistem Manajemen Piutang:** Modul pencatatan utang pelanggan yang terstruktur untuk memudahkan pelacakan, penagihan, dan kalkulasi total piutang yang masih berjalan.
*   **Laporan Keuangan Otomatis:** Pembuatan rekapitulasi arus kas masuk dan keluar secara otomatis berdasarkan transaksi harian, mempermudah evaluasi kesehatan finansial toko.
*   **Integrasi *Payment Gateway*:** Dukungan integrasi pembayaran digital (melalui Midtrans) untuk memperluas opsi transaksi yang lebih modern dan aman.

### 🛠️ Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi yang tangguh untuk memastikan stabilitas dan performa aplikasi:
*   **Backend:** PHP (Laravel Framework)
*   **Database:** MySQL
*   **Arsitektur & Konsep:** Progressive Web App (PWA) dan Integrasi REST API
*   **Perancangan:** Wireframing (Balsamiq) & Database Design (DrawSQL)

## 👥 Kelompok B

Proyek ini dikembangkan oleh:
- **Arif Agung Saputra** (051379585)
- **Aura Rahma Ruchiati** (048691487)
- **Bernaditus Yudha Bayu Setiawan** (049462653)
- **Emelia Aprilyanti Panjaitan** (053229369)
- **William Surya Imanuel** (042808945)

## 👩‍🏫 Dosen Pembimbing
- **Fitria Amastini** (04001258)

---

## 🚀 Cara Menjalankan Program (Installation Guide)

Untuk menjalankan proyek web aplikasi ini di komputer lokal Anda, ikuti langkah-langkah berikut:

1. **Clone Repository**
   Buka terminal atau command prompt, lalu clone repository ini:
   ```bash
   git clone https://github.com/shaunai/smart-kelontong.git
   cd smart-kelontong
   ```


2. **Install Composer Dependencies**
    Unduh dan instal seluruh dependensi framework PHP:
    ```bash
    composer install
    
    ```


3. **Install NPM Dependencies**
    Instal dependensi untuk frontend dan jalankan proses build:
    ```bash
    npm install
    npm run build
    
    ```


4. **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env` untuk mengatur konfigurasi lokal aplikasi:
    ```bash
    cp .env.example .env
    
    ```


5. **Generate Application Key**
    Buat *key* enkripsi unik untuk aplikasi:
    ```bash
    php artisan key:generate
    
    ```


6. **Konfigurasi Database**
    Buat database baru di MySQL (misalnya melalui XAMPP/phpMyAdmin). Buka file `.env` di teks editor Anda, lalu sesuaikan bagian koneksi database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    
    ```


7. **Migrasi Database**
    Buat tabel-tabel yang dibutuhkan ke dalam database:
    ```bash
    php artisan migrate
    
    ```


8. **Jalankan Server Lokal**
    Nyalakan server bawaan Laravel:
    ```bash
    php artisan serve
    
    ```


Aplikasi sekarang sudah berjalan dan dapat diakses melalui browser di `http://localhost:8000`

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

* [Simple, fast routing engine](https://laravel.com/docs/routing).
* [Powerful dependency injection container](https://laravel.com/docs/container).
* Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
* Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
* Database agnostic [schema migrations](https://laravel.com/docs/migrations).
* [Robust background job processing](https://laravel.com/docs/queues).
* [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

* **[Vehikl](https://vehikl.com)**
* **[Tighten Co.](https://tighten.co)**
* **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
* **[64 Robots](https://64robots.com)**
* **[Curotec](https://www.curotec.com/services/technologies/laravel)**
* **[DevSquad](https://devsquad.com/hire-laravel-developers)**
* **[Redberry](https://redberry.international/laravel-development)**
* **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](https://www.google.com/search?q=mailto%3Ataylor%40laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```

```
