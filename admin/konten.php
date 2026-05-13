<?php require_once __DIR__ . '/header.php'; ?>
<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $fields = [
        'hero_title', 'hero_subtitle', 'event_date',
        'about_title', 'about_text',
        'location_title', 'location_name', 'location_address', 'location_maps_embed',
        'instagram_1_url', 'instagram_1_name', 'instagram_2_url', 'instagram_2_name',
        'contact_title', 'footer_text',
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            set_setting($field, trim($_POST[$field]));
        }
    }
    $message = 'Konten berhasil diperbarui!';
}

// Load current values
$fields = [
    'hero_title', 'hero_subtitle', 'event_date',
    'about_title', 'about_text',
    'location_title', 'location_name', 'location_address', 'location_maps_embed',
    'instagram_1_url', 'instagram_1_name', 'instagram_2_url', 'instagram_2_name',
    'contact_title', 'footer_text',
];
$values = [];
foreach ($fields as $f) {
    $values[$f] = get_setting($f);
}
?>

<div class="admin-header">
    <h1>Kelola Konten</h1>
</div>

<?php if ($message): ?>
<div class="admin-alert success"><?= e($message) ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <?= csrf_field() ?>

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Hero Section</h3>
    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="hero_title" value="<?= e($values['hero_title']) ?>">
    </div>
    <div class="form-group">
        <label>Sub-judul</label>
        <input type="text" name="hero_subtitle" value="<?= e($values['hero_subtitle']) ?>">
    </div>
    <div class="form-group">
        <label>Tanggal Event</label>
        <input type="text" name="event_date" value="<?= e($values['event_date']) ?>" placeholder="YYYY-MM-DD">
    </div>

    <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Tentang Event</h3>
    <div class="form-group">
        <label>Judul Section</label>
        <input type="text" name="about_title" value="<?= e($values['about_title']) ?>">
    </div>
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="about_text"><?= e($values['about_text']) ?></textarea>
    </div>

    <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Lokasi</h3>
    <div class="form-group">
        <label>Judul Section</label>
        <input type="text" name="location_title" value="<?= e($values['location_title']) ?>">
    </div>
    <div class="form-group">
        <label>Nama Lokasi</label>
        <input type="text" name="location_name" value="<?= e($values['location_name']) ?>">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="location_address" value="<?= e($values['location_address']) ?>">
    </div>
    <div class="form-group">
        <label>Google Maps Embed URL</label>
        <input type="url" name="location_maps_embed" value="<?= e($values['location_maps_embed']) ?>">
    </div>

    <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Instagram</h3>
    <div class="form-group">
        <label>Instagram 1 — Nama</label>
        <input type="text" name="instagram_1_name" value="<?= e($values['instagram_1_name']) ?>">
    </div>
    <div class="form-group">
        <label>Instagram 1 — URL</label>
        <input type="url" name="instagram_1_url" value="<?= e($values['instagram_1_url']) ?>">
    </div>
    <div class="form-group">
        <label>Instagram 2 — Nama</label>
        <input type="text" name="instagram_2_name" value="<?= e($values['instagram_2_name']) ?>">
    </div>
    <div class="form-group">
        <label>Instagram 2 — URL</label>
        <input type="url" name="instagram_2_url" value="<?= e($values['instagram_2_url']) ?>">
    </div>

    <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Lainnya</h3>
    <div class="form-group">
        <label>Judul Form Pendaftaran</label>
        <input type="text" name="contact_title" value="<?= e($values['contact_title']) ?>">
    </div>
    <div class="form-group">
        <label>Footer Text</label>
        <input type="text" name="footer_text" value="<?= e($values['footer_text']) ?>">
    </div>

    <button type="submit" class="btn-admin">Simpan Perubahan</button>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
