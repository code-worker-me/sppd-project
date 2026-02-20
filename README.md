# SPPD — Sistem Manajemen Surat Perintah Perjalanan Dinas

**Deskripsi**

SPPD adalah aplikasi web berbasis Laravel untuk manajemen Surat Perintah Perjalanan Dinas (SPPD). Aplikasi ini menyediakan antarmuka admin (Filament) dan komponen interaktif (Livewire) untuk mengelola data pegawai, data perjalanan, dan dokumen SPPD secara terstruktur.

**Fitur**

- **Manajemen Data SPPD:** Buat, lihat, edit, dan hapus entri SPPD.
- **Data Diri Pegawai:** Simpan dan kelola data personal pegawai yang terkait dengan SPPD.
- **Data Perjalanan:** Kelola informasi perjalanan (tujuan, tanggal, biaya, dsb.).
- **Antarmuka Admin Filament:** Dashboard admin untuk CRUD dan manajemen data.
- **Komponen Livewire:** Interaksi UI dinamis tanpa refresh halaman penuh.
- **Migrasi & Seeder:** Struktur database dikemas dengan migrasi dan seeder dasar.

**Persyaratan Sistem**

- PHP 8.2+ (sesuaikan dengan `composer.json` jika diperlukan)
- Composer
- Node.js & npm
- MySQL / MariaDB atau database lain yang didukung Laravel

**Installasi (lokal)**

1. Clone repositori:
    ```bash
	git clone https://github.com/code-worker-me/sppd-project.git
	cd sppd
    ```
2. Pasang dependensi PHP:
    ```bash
	composer install
    ```
3. Salin file environment dan atur konfigurasi database:
    ```bash
	copy .env.example .env
	(Edit `.env` — atur `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
    ```
4. Buat key aplikasi dan jalankan migrasi:
    ```bash
	php artisan key:generate
	php artisan migrate --seed
    ```
5. Pasang dependensi frontend dan bangun aset:
    ```bash
	npm install
	npm run dev   # untuk pengembangan
	npm run build # untuk produksi
    ```
6. (Opsional) Buat symbolic link storage:
    ```bash
	php artisan storage:link
    ```
7. Jalankan server lokal (contoh Laragon / artisan):
    ```bash
	php artisan serve --port=8000
    ```
Catatan: Untuk lingkungan Windows + Laragon, Anda juga bisa menjalankan melalui GUI Laragon atau menaruh project di folder www.

**Work In Progress (Perkembangan)**

- Penulisan unit & feature test untuk alur SPPD.
- Fitur export/import (CSV/Excel) untuk data SPPD.
- Penyempurnaan antarmuka dan validasi formulir.
- CI/CD pipeline untuk build dan pengujian otomatis.

---

Dokumentasi ini telah dibuat berdasarkan struktur proyek di repositori saat ini. Untuk referensi kode lihat direktori `app/`, `database/migrations/`, dan `app/Filament/Resources/`.

