# INSTALL WAOCA – SIMRS Khanza / OCA WhatsApp

Panduan instalasi aplikasi WAOCA untuk server PHP + MariaDB/MySQL.

> **PENTING:** Jangan memasukkan password database, API token OCA, client secret, atau credential server ke GitHub.

## 1. Persyaratan server

Disarankan:
- Linux + Apache/Nginx
- PHP 7.4+ (sesuaikan dengan aplikasi)
- MariaDB/MySQL
- PHP extensions: mysqli, curl, json, mbstring
- Composer jika source WAOCA menggunakan library Composer
- Akses jaringan ke API OCA Telkom
- Database SIMRS Khanza dan database WAOCA/SIMIFM

GitHub Pages hanya menjalankan HTML/CSS/JavaScript. **PHP + database harus dijalankan di server PHP**, bukan di GitHub Pages.

## 2. Struktur database

Pisahkan database aplikasi WAOCA dari database SIMRS bila memungkinkan.

Contoh:

```
SIMRS Khanza  -> database SIMRS
WAOCA/SIMIFM  -> database aplikasi WAOCA
```

WAOCA membaca data SIMRS seperlunya, sedangkan tabel antrean/template/log WhatsApp ditempatkan di database aplikasi.

## 3. Buat database

Contoh:

```sql
CREATE DATABASE IF NOT EXISTS simifm
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

Kemudian buka:

**WAOCA/database/install.sql**

dan jalankan pada database `simifm`.

## 4. Tabel utama

Instalasi menyediakan tabel dasar:

- `wa_template` — menyimpan template pesan WhatsApp.
- `wa_outbox` — antrean pesan yang akan dikirim.
- `simifm_user` — user aplikasi WAOCA.
- index/status untuk membantu proses antrean.

Sebelum production, cocokkan struktur kolom dengan source WAOCA yang digunakan di rumah sakit. Jika aplikasi Anda sudah memiliki tabel-tabel tersebut, **jangan menjalankan CREATE TABLE ulang tanpa backup**.

## 5. Konfigurasi database

Di server, buka:

```
WAOCA/config.php
```

Isi:

```php
$serverSIMRS   = 'HOST_SIMRS';
$userSIMRS     = 'USER_SIMRS';
$passSIMRS     = 'PASSWORD_SIMRS';
$databaseSIMRS = 'NAMA_DATABASE_SIMRS';

$serverSIMIFM   = 'HOST_WAOCA';
$userSIMIFM     = 'USER_WAOCA';
$passSIMIFM     = 'PASSWORD_WAOCA';
$databaseSIMIFM = 'simifm';
```

**Jangan commit file yang sudah berisi password ke GitHub.**

Gunakan environment variable jika server mendukungnya.

## 6. Konfigurasi OCA WhatsApp

Token OCA tidak boleh ditulis ke GitHub.

Gunakan environment variable:

```
OCA_WHATSAPP_TOKEN=ISI_TOKEN_OCA
```

Endpoint dan parameter harus mengikuti akun/API OCA yang diberikan Telkom.

## 7. Import template WhatsApp

Setelah tabel dibuat:

1. Masuk ke database WAOCA.
2. Buka tabel `wa_template`.
3. Masukkan template yang sudah **approved oleh OCA**.
4. Pastikan `template_code` sama persis dengan template yang digunakan pada payload API.
5. Urutan variable harus sama dengan urutan variable pada template OCA.

Contoh alur:

```
Nama template
       |
       v
wa_template
       |
       v
program WAOCA
       |
       v
wa_outbox
       |
       v
OCA WhatsApp API
       |
       v
WhatsApp pasien
```

## 8. Proses wa_outbox

Aplikasi sebaiknya tidak mengirim ratusan pesan langsung dalam satu request halaman.

Alur yang dianjurkan:

1. Program membuat data pesan ke `wa_outbox`.
2. Worker/cron mengambil pesan berstatus `PENDING`.
3. Worker mengirim ke OCA.
4. Jika berhasil, ubah status menjadi `SENT`.
5. Jika gagal, simpan error dan jumlah percobaan.
6. Jangan mengirim ulang pesan yang sudah `SENT`.

Untuk beban besar, gunakan batch kecil, misalnya 10–50 pesan per proses, dengan jeda sesuai batas API OCA.

## 9. Test koneksi database

Setelah config diisi di server:

```
https://DOMAIN-ANDA/WAOCA/test-koneksi.php
```

Jika berhasil, halaman harus menunjukkan koneksi database berhasil.

Setelah testing selesai, sebaiknya hapus atau lindungi file test tersebut.

## 10. Test template

Buat satu template test yang sudah approved OCA.

Pastikan:
- template code benar
- language benar
- parameter variable benar
- nomor tujuan menggunakan format yang diwajibkan OCA
- token OCA valid

Jangan melakukan blast sebelum satu pesan test berhasil.

## 11. Test wa_outbox

Masukkan satu record test ke `wa_outbox`.

Periksa:

```sql
SELECT *
FROM wa_outbox
ORDER BY id DESC
LIMIT 20;
```

Periksa status setelah worker berjalan.

Contoh status yang dapat digunakan:

```
PENDING
PROCESSING
SENT
FAILED
```

Jika source aplikasi Anda sudah mempunyai nama status sendiri, **ikuti status yang digunakan source tersebut**.

## 12. Cron / worker

Jika WAOCA menyediakan script worker, jalankan menggunakan cron.

Contoh:

```bash
*/1 * * * * /usr/bin/php /opt/lampp/htdocs/WAOCA/worker.php >> /var/log/waoca.log 2>&1
```

Sesuaikan lokasi PHP dan file worker dengan server.

Jangan menyalin contoh cron di atas tanpa memastikan nama file worker memang ada.

## 13. Backup

Sebelum production:

```bash
mysqldump simifm > simifm-backup.sql
```

Backup juga database SIMRS sebelum membuat perubahan tabel atau bridging.

## 14. Checklist production

- [ ] Database WAOCA dibuat.
- [ ] `install.sql` sudah dijalankan.
- [ ] Struktur tabel dicocokkan dengan source aplikasi.
- [ ] `config.php` diisi **hanya di server**.
- [ ] Password tidak ada di GitHub.
- [ ] Token OCA tidak ada di GitHub.
- [ ] Template OCA sudah approved.
- [ ] `wa_template` sudah diisi.
- [ ] `wa_outbox` sudah dites.
- [ ] Satu pesan test berhasil.
- [ ] Worker/cron sudah berjalan.
- [ ] Log error sudah dapat dipantau.
- [ ] Backup database tersedia.
- [ ] File test koneksi diamankan setelah instalasi.

## 15. Catatan untuk SIMRS Khanza

Untuk bridging Khanza, lebih aman membuat middleware WAOCA yang membaca data yang diperlukan dari database SIMRS, kemudian memasukkan pesan ke `wa_outbox`.

Jangan mengubah tabel inti Khanza jika tidak diperlukan.

Contoh alur:

```
SIMRS Khanza
     |
     | data pasien / dokter / jadwal
     v
Middleware WAOCA
     |
     v
wa_template + wa_outbox
     |
     v
OCA Telkom
     |
     v
WhatsApp
```

## 16. Keamanan

Repository ini adalah tempat source code, **bukan tempat menyimpan secret**.

Jangan commit:
- password database
- API token OCA
- private key
- client secret
- password administrator
- file `.env` production

Jika credential pernah terlanjur masuk GitHub, **ganti/rotate credential tersebut**, jangan hanya menghapusnya dari file.
