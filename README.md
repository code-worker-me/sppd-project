# SPPD (Surat Perintah Perjalanan Dinas)

Ringkasan singkat aplikasi SPPD — aplikasi Laravel untuk mengelola data perjalanan dinas, SPPD, dan ekspor laporan ke Excel.

**Kode sumber utama**
- Backend: Laravel (folder `app/`, `routes/`)
- Export Excel: [app/Exports/SppdExport.php](app/Exports/SppdExport.php)

Persyaratan
- PHP 8.x
- Composer
- Node.js & npm
- Database (MySQL / MariaDB / PostgreSQL)

Instalasi & setup (lokal)

1. Clone repository dan masuk ke folder project

```bash
git clone <repo-url> sppd
cd sppd
```

2. Install dependensi PHP dan JS

```bash
composer install
npm install
```

3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
# edit .env untuk konfigurasi database dan mail
```

4. Migrasi database dan (opsional) seeder

```bash
php artisan migrate --seed
```

5. Link storage (jika perlu)

```bash
php artisan storage:link
```

6. Build aset (development / production)

```bash
npm run dev   # atau npm run build
```

7. Jalankan server lokal

```bash
php artisan serve
# atau gunakan Laragon / Valet sesuai lingkungan Anda
```

Export Excel
- Export Excel di-handle oleh kelas `App\Exports\SppdExport`. File: [app/Exports/SppdExport.php](app/Exports/SppdExport.php).
- Kelas mendukung filter tahun dan bulan lewat konstruktor: `new SppdExport($year, $month)`.
- Format kolom menampilkan mata uang (Rp) dan lebar kolom khusus untuk kolom deskripsi.

Contoh penggunaan (controller):

```php
use App\Exports\SppdExport;
use Maatwebsite\Excel\Facades\Excel;

return Excel::download(new SppdExport(2026, 3), 'sppd_maret_2026.xlsx');
```

Testing

```bash
php artisan test
# atau
./vendor/bin/pest
```

Catatan penting
- Periksa `config/excel.php` untuk pengaturan ekspor.
- Model utama terkait: `App\Models\DataPerjalanan`, `App\Models\Sppd`, `App\Models\User`.
- Jika menambahkan kolom baru pada export, sesuaikan `headings()` dan `map()` di `SppdExport`.

Kontribusi
- Silakan ajukan pull request. Ikuti standar PSR dan jalankan `composer lint` / `npm run lint` jika ada.

Kontak
- Untuk pertanyaan atau bantuan, tambahkan issue pada repository.

---
Generated: updated README sesuai struktur project saat ini.
