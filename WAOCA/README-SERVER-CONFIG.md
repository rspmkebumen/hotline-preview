# WAOCA - Server Configuration

Folder ini berisi konfigurasi dan sumber aplikasi WAOCA.

## Keamanan
- Password database sengaja dikosongkan.
- Token OCA WhatsApp sengaja tidak disimpan di GitHub.
- Host, username, password, dan nama database harus diisi pada server production.
- Token OCA sebaiknya diberikan melalui environment variable `OCA_WHATSAPP_TOKEN`.

## Database
WAOCA menggunakan dua koneksi:
1. SIMRS — untuk membaca data pasien, dokter, jadwal, dan poliklinik.
2. SIMIFM — untuk template WhatsApp, outbox, dan user aplikasi.

## Catatan
GitHub Pages tidak menjalankan PHP. File PHP WAOCA harus dijalankan pada server PHP/MySQL/LAMP/XAMPP production.
