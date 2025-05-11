# 🛠️ RT Administration API – Backend (Laravel 12)

Aplikasi backend untuk sistem administrasi RT menggunakan Laravel 12.

## ✅ Requirement

- PHP >= 8.2
- Composer >= 2.5
- MySQL >= 8.0

## ⚙️ Langkah Instalasi

```bash
# 1. Clone repository dan masuk ke direktori
git clone <URL-REPO>
cd backend

# 2. Install dependency
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Konfigurasi koneksi database di file .env
# Contoh:
# DB_DATABASE=rt_administration
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Generate APP_KEY dan migrasi database
php artisan key:generate
php artisan migrate --seed
# Catatan: Perintah php artisan migrate --seed akan menjalankan seeder untuk mengisi database dengan data dummy/random (misalnya rumah, penghuni, histori, pembayaran) sebagai simulasi sistem produksi.

# 6. Jalankan server lokal
php artisan serve
