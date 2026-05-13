# Revitalisasi Ulos Inklusif

Website resmi pameran **Revitalisasi Ulos Inklusif untuk Pariwisata Berkelanjutan di Toba Kaldera** — menampilkan perpaduan ulos Batak dan batik bermotif Batak dalam desain busana modern dan inklusif.

> **17 Mei 2026** | Nimo Kaldera, Toba Caldera Resort, Sumatera Utara

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-All%20Rights%20Reserved-red)

---

## Tentang

Karya "Revitalisasi Ulos Inklusif" menghadirkan rancangan busana pria dan wanita yang memadukan keindahan motif ulos oleh perajin Desa Meat dengan estetika batik motif Batak dalam tampilan modern dan inklusif. Perpaduan dua warisan budaya ini menjadi simbol harmonisasi tradisi lokal dengan perkembangan desain kontemporer, sekaligus memperkuat identitas budaya kawasan Toba Kaldera.

### Tim Kreatif — Universitas Negeri Medan
- Dr. Wahyu Tri Atmojo, M.Hum.
- Nur Basuki, S.Pd., M.Pd., M.Pd.T
- Dr. Daulat Saragi, M.Hum.
- Dr. M. Surip, S.Pd., M.Si.
- Muslim, S.Pd., M.Pd.
- Dr. Muslim, S.T., M.Pd.
- Rinanda Purba, S.Kom., M.Sn.

### Penyelenggara
- Kementerian Kebudayaan Republik Indonesia
- Dana Indonesiana
- LPDP (Lembaga Pengelola Dana Pendidikan)

### Mitra
- Sanggar Seni Pendopo
- Desa Meat
- Nimo Kaldera — Toba Caldera Resort
- BPODT (Badan Pelaksana Otorita Danau Toba)

---

## Fitur Website

- **Landing Page** — Single-page responsive dengan tema dark & gold
- **Countdown Timer** — Hitung mundur ke tanggal acara
- **Hero Video Background** — Video autoplay di section pembuka
- **Video Produk Karya Inovatif** — Showcase video dengan layout featured
- **Galeri Koleksi** — Grid gallery dengan lightbox viewer
- **Nimo Kaldera Showcase** — Section ajakan dengan foto venue
- **Agenda Acara** — Timeline agenda yang dikelola via admin
- **Formulir Pendaftaran** — AJAX form dengan validasi
- **Google Maps Embed** — Lokasi Nimo Kaldera
- **Admin Panel** — Kelola semua konten, gallery, sponsor, agenda, dan pendaftar

---

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8 Native (tanpa framework) |
| Database | MySQL 5.7+ / MariaDB |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Font | Google Fonts (Playfair Display + Poppins) |
| Server | Apache (XAMPP / cPanel) |

### Keamanan
- PDO Prepared Statements (anti SQL Injection)
- CSRF Token pada semua form
- XSS Protection via `htmlspecialchars()`
- Password hashing dengan `bcrypt`
- Session-based authentication

---

## Instalasi

### Prasyarat
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache dengan mod_rewrite
- XAMPP (untuk development lokal)

### Langkah

1. **Clone repository**
   ```bash
   git clone https://github.com/USERNAME/revitalisasiulosinklusif.git
   ```

2. **Pindahkan ke htdocs**
   ```bash
   # Windows (XAMPP)
   cp -r revitalisasiulosinklusif /c/xampp/htdocs/
   ```

3. **Konfigurasi database** — edit `config.php`
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'revitalisasi_ulos');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Setup otomatis** — buka di browser
   ```
   http://localhost/revitalisasiulosinklusif/setup.php
   ```
   Script ini otomatis:
   - Membuat semua tabel database (settings, agenda, gallery, sponsors, registrations, admin_users)
   - Insert data default
   - Membuat direktori upload
   - Membuat admin user default

5. **Buka website**
   ```
   http://localhost/revitalisasiulosinklusif/
   ```

### Login Admin
```
URL  : http://localhost/revitalisasiulosinklusif/admin/
User : admin
Pass : ad312
```
> **Penting:** Ganti password default setelah login pertama!

---

## Struktur Folder

```
revitalisasiulosinklusif/
├── admin/                  # Admin panel
│   ├── index.php           # Login page
│   ├── dashboard.php       # Dashboard statistik
│   ├── konten.php          # Edit konten website
│   ├── agenda.php          # CRUD agenda
│   ├── gallery.php         # Upload/hapus gallery
│   ├── sponsors.php        # CRUD sponsor
│   ├── pendaftar.php       # Lihat pendaftar + export CSV
│   └── css/admin.css       # Admin stylesheet
├── css/
│   └── style.css           # Main stylesheet
├── js/
│   └── main.js             # Countdown, animations, lightbox
├── includes/
│   ├── db.php              # PDO database connection
│   └── functions.php       # Helper functions
├── logo/                   # Logo penyelenggara & mitra
│   └── team/               # Foto tim kreatif
├── video/                  # Video showcase
├── uploads/
│   ├── gallery/            # Foto galeri
│   └── sponsors/           # Logo sponsor
├── dokumentasi/            # Foto kegiatan Nimo Kaldera
│   └── sponsor/            # Foto dokumentasi sponsor
├── config.php              # Konfigurasi database
├── index.php               # Landing page utama
├── register.php            # API endpoint pendaftaran
├── setup.php               # Auto-setup database
└── revitalisasi_ulos.sql   # SQL dump (backup)
```

---

## Deploy ke Hosting

1. Upload semua file via File Manager / FTP
2. Buat database MySQL di cPanel
3. Edit `config.php` dengan kredensial hosting
4. Akses `setup.php` sekali untuk inisialisasi
5. Hapus `setup.php` setelah selesai (keamanan)

---

## Screenshot

### Landing Page
- Hero dengan video background & countdown timer
- Tema dark & gold yang elegan
- Responsive di semua device

### Admin Panel
- Dashboard dengan statistik
- Kelola konten, agenda, gallery, sponsor
- Export data pendaftar ke CSV

---

## Lisensi

All Rights Reserved. Proyek ini dibuat untuk kegiatan **Revitalisasi Ulos Inklusif** oleh Tim Kreatif Universitas Negeri Medan dengan dukungan Kementerian Kebudayaan RI, Dana Indonesiana, dan LPDP.
