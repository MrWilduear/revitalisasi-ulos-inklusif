<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

$db = get_db();

// Get all settings
$hero_title = get_setting('hero_title', 'Revitalisasi Ulos Inklusif');
$hero_subtitle = get_setting('hero_subtitle', 'Penciptaan Karya Kreatif Inovatif untuk Pariwisata Berkelanjutan di Toba Kaldera');
$event_date = get_setting('event_date', '2026-05-17');
$about_title = get_setting('about_title', 'Tentang Event');
$about_text = get_setting('about_text', '');
$location_title = get_setting('location_title', 'Kapan & Dimana');
$location_name = get_setting('location_name', 'Nimo Kaldera, Toba Caldera Resort');
$location_address = get_setting('location_address', '');
$location_maps_embed = get_setting('location_maps_embed', '');
$instagram_1_url = get_setting('instagram_1_url', '');
$instagram_1_name = get_setting('instagram_1_name', '@nimokaldera');
$instagram_2_url = get_setting('instagram_2_url', '');
$instagram_2_name = get_setting('instagram_2_name', '@tobacalderaresort');
$contact_title = get_setting('contact_title', 'Daftar Sekarang');
$footer_text = get_setting('footer_text', '');

// Get agenda
$stmt = $db->query('SELECT * FROM agenda ORDER BY urutan ASC');
$agenda_items = $stmt->fetchAll();

// Get gallery from DB
$stmt = $db->query('SELECT * FROM gallery ORDER BY urutan ASC');
$gallery_items = $stmt->fetchAll();

// If DB gallery empty, scan filesystem
if (empty($gallery_items)) {
    $gallery_dir = __DIR__ . '/uploads/gallery/';
    if (is_dir($gallery_dir)) {
        $files = glob($gallery_dir . '*.{png,jpg,jpeg,webp,gif}', GLOB_BRACE);
        sort($files);
        foreach ($files as $i => $file) {
            $basename = basename($file);
            if ($basename === '.gitkeep') continue;
            $gallery_items[] = [
                'gambar' => $basename,
                'caption' => pathinfo($basename, PATHINFO_FILENAME),
            ];
        }
    }
}

// Get sponsors grouped by tier
$stmt = $db->query('SELECT * FROM sponsors ORDER BY FIELD(tier, "platinum", "gold", "silver", "bronze"), urutan ASC');
$all_sponsors = $stmt->fetchAll();
$sponsors_by_tier = [];
foreach ($all_sponsors as $s) {
    $sponsors_by_tier[$s['tier']][] = $s;
}

// CSRF for registration form
$csrf = csrf_token();

// Team members
$team = [
    ['name' => 'Dr. Wahyu Tri Atmojo, M.Hum.', 'photo' => 'logo/team/wahyu.jpg'],
    ['name' => 'Nur Basuki, S.Pd., M.Pd., M.Pd.T', 'photo' => 'logo/team/basuki.jpg'],
    ['name' => 'Dr. Daulat Saragi, M.Hum.', 'photo' => 'logo/team/daulat.jpg'],
    ['name' => 'Dr. M. Surip, S.Pd., M.Si.', 'photo' => 'logo/team/surip.jpg'],
    ['name' => 'Muslim, S.Pd., M.Pd.', 'photo' => 'logo/team/muslim_spd.png'],
    ['name' => 'Dr. Muslim, S.T., M.Pd.', 'photo' => 'logo/team/muslim_st.jpg'],
    ['name' => 'Rinanda Purba, S.Kom., M.Sn.', 'photo' => 'logo/team/rinanda.jpg'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($hero_title) ?> — Toba Kaldera</title>
    <meta name="description" content="Revitalisasi Ulos Inklusif untuk Pariwisata Berkelanjutan di Toba Kaldera. Penciptaan Karya Kreatif Inovatif oleh Komunitas Pecinta Ulos.">
    <link rel="icon" href="logo/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="#" class="navbar-logo">
        <img src="logo/logo.png" alt="Revitalisasi Ulos">
        <img src="logo/indonesiana-.png" alt="Logo Tambahan">
        <img src="logo/logolpdp-.png" alt="Nimo Kaldera">
    </a>
    <ul class="navbar-menu">
        <li><a href="#tentang">Tentang</a></li>
        <li><a href="#penyelenggara">Mitra & Penyelenggara</a></li>
        <li><a href="#video-showcase">Video</a></li>
        <li><a href="#agenda">Agenda</a></li>
        <li><a href="#galeri">Galeri</a></li>
        <li><a href="#lokasi">Lokasi</a></li>
        <li><a href="#daftar">Daftar</a></li>
    </ul>
    <div class="navbar-toggle">
        <span></span><span></span><span></span>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="video/video.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <img src="logo/logorevitalisasi.png" alt="<?= e($hero_title) ?>" class="hero-logo">
        <p class="hero-subtitle">Penciptaan Karya Kreatif Inovatif untuk Pariwisata Berkelanjutan di Toba Kaldera</p>
        <input type="hidden" id="event-date" value="<?= e($event_date) ?>">
        <div class="countdown">
            <div class="countdown-item">
                <span class="countdown-value" id="countdown-days">0</span>
                <span class="countdown-label">Hari</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="countdown-hours">0</span>
                <span class="countdown-label">Jam</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="countdown-minutes">0</span>
                <span class="countdown-label">Menit</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="countdown-seconds">0</span>
                <span class="countdown-label">Detik</span>
            </div>
        </div>
        <a href="#daftar" class="btn-primary">Daftar Sekarang</a>
    </div>
</section>

<!-- Tentang -->
<section class="section section-alt" id="tentang">
    <h2 class="section-title fade-in"><?= e($about_title) ?></h2>
    <div class="section-divider fade-in"></div>
    <div class="about-content fade-in">
        Karya "Revitalisasi Ulos Inklusif untuk Pariwisata Berkelanjutan di Toba Kaldera" menghadirkan rancangan busana pria dan wanita yang memadukan keindahan motif ulos oleh perajin Meat dengan estetika batik motif batak dalam tampilan modern dan inklusif. Perpaduan dua warisan budaya ini menjadi simbol harmonisasi tradisi lokal dengan perkembangan desain kontemporer, sekaligus memperkuat identitas budaya kawasan Toba Kaldera.
        <br><br>
        Melalui pendekatan kreatif dan berkelanjutan, karya ini tidak hanya menonjolkan nilai estetis, tetapi juga mendukung pelestarian budaya, pemberdayaan perajin lokal, serta pengembangan pariwisata budaya yang berdaya saing dan ramah lingkungan.
    </div>

    <!-- Tim Kreatif -->
    <div class="fade-in" style="text-align:center; margin-top:50px;">
        <h3 style="font-family:var(--font-heading); font-size:1.3rem; color:var(--gold); margin-bottom:20px;">Tim Kreatif</h3>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
            <div class="team-member">
                <img src="<?= e($member['photo']) ?>" alt="<?= e($member['name']) ?>">
                <span><?= e($member['name']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Penyelenggara & Mitra -->
<section class="section" id="penyelenggara">
    <h2 class="section-title fade-in">Penyelenggara</h2>
    <div class="section-divider fade-in"></div>
    <div class="partners-grid fade-in">
        <div class="partner-item large">
            <img src="logo/translated_image_id.png" alt="Kementerian Kebudayaan Republik Indonesia">
        </div>
        <div class="partner-item">
            <img src="logo/indonesiana.png" alt="Dana Indonesiana">
        </div>
        <div class="partner-item">
            <img src="logo/logolpdp.png" alt="LPDP">
        </div>
    </div>

    <!-- Mitra -->
    <div class="fade-in" style="text-align:center; margin-top:60px;">
        <h3 style="font-family:var(--font-heading); font-size:1.3rem; color:var(--gold); margin-bottom:25px;">Mitra</h3>
        <div class="komunitas-grid">
            <div class="komunitas-card">
                <img src="logo/logokomunitaspencintaulos.png" alt="Sanggar Seni Pendopo">
                <h3>Sanggar Seni Pendopo</h3>
                <p>Komunitas Seni & Budaya</p>
            </div>
            <div class="komunitas-card">
                <img src="logo/desameat.png" alt="Desa Meat">
                <h3>Desa Meat</h3>
                <p>Sentra Perajin Ulos Tradisional</p>
            </div>
            <div class="komunitas-card">
                <img src="logo/logonimokaldera.png" alt="Nimo Kaldera">
                <h3>Nimo Kaldera</h3>
                <p>Toba Caldera Resort</p>
            </div>
            <div class="komunitas-card">
                <img src="logo/logobpodt.png" alt="BPODT">
                <h3>BPODT</h3>
                <p>Badan Pelaksana Otorita Danau Toba</p>
            </div>
        </div>
    </div>
</section>

<!-- Video Showcase -->
<section class="section section-alt" id="video-showcase">
    <h2 class="section-title fade-in">Video Produk Karya Inovatif</h2>
    <div class="section-divider fade-in"></div>

    <!-- Video utama besar -->
    <div class="video-featured fade-in">
        <video controls preload="metadata" playsinline>
            <source src="video/PRODUK ULOS BATIK.mp4" type="video/mp4">
        </video>
        <div class="video-featured-info">
            <h3>Produk Ulos Batik</h3>
            <p>Koleksi rancangan busana yang memadukan motif ulos perajin Meat dengan estetika batik motif Batak</p>
        </div>
    </div>

    <!-- 2 video kecil -->
    <div class="video-secondary fade-in">
        <div class="video-card">
            <video controls preload="metadata" playsinline>
                <source src="video/FOTO MODEL.mp4" type="video/mp4">
            </video>
            <div class="video-info">
                <h3>Peragaan Busana</h3>
                <p>Model menampilkan busana ulos inklusif</p>
            </div>
        </div>
        <div class="video-card">
            <video controls preload="metadata" playsinline>
                <source src="video/testimoni Wahyu Tri Atmojo.mp4" type="video/mp4">
            </video>
            <div class="video-info">
                <h3>Testimoni Kreator</h3>
                <p>Wahyu Tri Atmojo — Visi & Inspirasi</p>
            </div>
        </div>
    </div>
</section>

<!-- Agenda -->
<section class="section" id="agenda">
    <h2 class="section-title fade-in">Agenda Acara</h2>
    <div class="section-divider fade-in"></div>
    <div class="timeline">
        <?php foreach ($agenda_items as $item): ?>
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-time"><?= e($item['waktu']) ?></div>
            <div class="timeline-title"><?= e($item['judul']) ?></div>
            <div class="timeline-desc"><?= e($item['deskripsi']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Gallery -->
<section class="section section-alt" id="galeri">
    <h2 class="section-title fade-in">Galeri Koleksi</h2>
    <div class="section-divider fade-in"></div>
    <div class="gallery-grid fade-in">
        <?php if (empty($gallery_items)): ?>
        <div class="gallery-empty">Galeri akan segera tersedia</div>
        <?php else: ?>
            <?php foreach ($gallery_items as $item): ?>
            <div class="gallery-item">
                <img src="uploads/gallery/<?= e($item['gambar']) ?>" alt="<?= e($item['caption'] ?? '') ?>">
                <?php if (!empty($item['caption'])): ?>
                <div class="gallery-caption"><?= e($item['caption']) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <span class="lightbox-close">&times;</span>
    <img src="" alt="" id="lightbox-img">
</div>

<!-- Nimo Kaldera Showcase -->
<section class="section" id="nimo-kaldera">
    <h2 class="section-title fade-in">Nimo Kaldera — Toba Caldera Resort</h2>
    <div class="section-divider fade-in"></div>
    <div class="nimo-showcase fade-in">
        <div class="nimo-gallery">
            <div class="nimo-photo main">
                <img src="dokumentasi/nimo-kaldera-1.jpeg" alt="Nimo Kaldera">
            </div>
            <div class="nimo-photo">
                <img src="dokumentasi/nimo-kaldera-2.jpeg" alt="Nimo Kaldera">
            </div>
            <div class="nimo-photo">
                <img src="dokumentasi/nimo-kaldera-3.jpeg" alt="Nimo Kaldera">
            </div>
        </div>
        <div class="nimo-info">
            <h3>Venue Eksklusif di Jantung Toba Kaldera</h3>
            <p>Nikmati keindahan alam Danau Toba dari Nimo Kaldera — resort premium di tepi kaldera supervolcano terbesar di dunia. Tempat sempurna di mana budaya Batak bertemu keindahan alam luar biasa.</p>
            <p>Saksikan langsung pameran <strong>Revitalisasi Ulos Inklusif</strong> di venue spektakuler ini pada <strong>17 Mei 2026</strong>.</p>
            <div class="nimo-cta">
                <a href="https://www.instagram.com/nimokaldera/" target="_blank" rel="noopener" class="btn-nimo">@nimokaldera</a>
                <a href="#daftar" class="btn-nimo btn-nimo-primary">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</section>

<!-- Sponsor Dokumentasi -->
<section class="section section-alt" id="sponsor-galeri">
    <h2 class="section-title fade-in">Kegiatan Nimo Kaldera</h2>
    <div class="section-divider fade-in"></div>
    <div class="sponsor-gallery fade-in">
        <?php for ($i = 1; $i <= 7; $i++): ?>
        <div class="sponsor-gallery-item">
            <img src="dokumentasi/sponsor/sponsor-<?= $i ?>.jpeg" alt="Dokumentasi <?= $i ?>">
        </div>
        <?php endfor; ?>
    </div>
</section>

<!-- Lokasi -->
<section class="section" id="lokasi">
    <h2 class="section-title fade-in"><?= e($location_title) ?></h2>
    <div class="section-divider fade-in"></div>
    <div class="location-grid fade-in">
        <div class="location-info">
            <h3><?= e($location_name) ?></h3>
            <p><?= e($location_address) ?></p>
            <p><strong>Tanggal:</strong> <?= date('d F Y', strtotime($event_date)) ?></p>
            <img src="logo/logonimokaldera.png" alt="Nimo Kaldera" class="location-logo">
            <div class="location-links">
                <a href="<?= e($instagram_1_url) ?>" target="_blank" rel="noopener"><?= e($instagram_1_name) ?></a>
                <a href="<?= e($instagram_2_url) ?>" target="_blank" rel="noopener"><?= e($instagram_2_name) ?></a>
            </div>
        </div>
        <div class="location-map">
            <?php if ($location_maps_embed): ?>
            <iframe src="<?= e($location_maps_embed) ?>" allowfullscreen loading="lazy"></iframe>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Sponsors -->
<section class="section section-alt" id="sponsor">
    <h2 class="section-title fade-in">Didukung Oleh</h2>
    <div class="section-divider fade-in"></div>
    <?php if (empty($all_sponsors)): ?>
    <div class="sponsors-empty fade-in">Segera hadir</div>
    <?php else: ?>
        <?php
        $tier_labels = ['platinum' => 'Platinum', 'gold' => 'Gold', 'silver' => 'Silver', 'bronze' => 'Bronze'];
        foreach ($tier_labels as $tier => $label):
            if (!isset($sponsors_by_tier[$tier])) continue;
        ?>
        <div class="sponsors-tier fade-in">
            <div class="sponsors-tier-title"><?= $label ?></div>
            <div class="sponsors-grid">
                <?php foreach ($sponsors_by_tier[$tier] as $sponsor): ?>
                <a href="<?= e($sponsor['website'] ?: '#') ?>" target="_blank" rel="noopener" class="sponsor-item <?= $tier ?>">
                    <img src="uploads/sponsors/<?= e($sponsor['logo']) ?>" alt="<?= e($sponsor['nama']) ?>">
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- Instagram -->
<section class="section" id="instagram">
    <h2 class="section-title fade-in">Ikuti Kami</h2>
    <div class="section-divider fade-in"></div>
    <div class="instagram-grid fade-in">
        <div class="instagram-card">
            <h3><?= e($instagram_1_name) ?></h3>
            <p>Nimo Kaldera — Venue partner</p>
            <a href="<?= e($instagram_1_url) ?>" target="_blank" rel="noopener" class="btn-ig">Kunjungi Instagram</a>
        </div>
        <div class="instagram-card">
            <h3><?= e($instagram_2_name) ?></h3>
            <p>Toba Caldera Resort — Venue partner</p>
            <a href="<?= e($instagram_2_url) ?>" target="_blank" rel="noopener" class="btn-ig">Kunjungi Instagram</a>
        </div>
    </div>
</section>

<!-- Registration -->
<section class="section section-alt" id="daftar">
    <h2 class="section-title fade-in"><?= e($contact_title) ?></h2>
    <div class="section-divider fade-in"></div>
    <form class="register-form fade-in" id="register-form">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <div id="form-message" class="form-message"></div>
        <div class="form-group">
            <label for="nama">Nama Lengkap *</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="telepon">Nomor Telepon</label>
            <input type="tel" id="telepon" name="telepon">
        </div>
        <div class="form-group">
            <label for="instansi">Instansi / Organisasi</label>
            <input type="text" id="instansi" name="instansi">
        </div>
        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan"></textarea>
        </div>
        <button type="submit" class="btn-primary" style="width:100%">Kirim Pendaftaran</button>
    </form>
</section>

<!-- Footer -->
<footer class="footer">
    <img src="logo/logo.png" alt="Logo" class="footer-logo">
    <div class="footer-text">Revitalisasi Ulos Inklusif</div>
    <p style="color:var(--text-secondary); font-size:0.85rem; margin-bottom:15px;">Komunitas Pecinta Ulos — Penciptaan Karya Kreatif Inovatif</p>
    <div class="footer-links">
        <a href="#tentang">Tentang</a>
        <a href="#penyelenggara">Mitra</a>
        <a href="#agenda">Agenda</a>
        <a href="#galeri">Galeri</a>
        <a href="#daftar">Daftar</a>
    </div>
    <div style="display:flex; justify-content:center; align-items:center; gap:20px; margin:20px 0; flex-wrap:wrap;">
        <img src="logo/translated_image_id.png" alt="Kemendikbud" style="height:35px; opacity:0.7;">
        <img src="logo/logolpdp.png" alt="LPDP" style="height:25px; opacity:0.7;">
        <img src="logo/indonesiana.png" alt="Indonesiana" style="height:20px; opacity:0.7;">
        <img src="logo/logobpodt.png" alt="BPODT" style="height:35px; opacity:0.7;">
    </div>
    <div class="footer-copy"><?= e($footer_text) ?></div>
</footer>

<script src="js/main.js"></script>
</body>
</html>
