<?php require_once __DIR__ . '/header.php'; ?>
<?php
$db = get_db();
$message = '';
$upload_dir = __DIR__ . '/../uploads/gallery/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload' && isset($_FILES['gambar'])) {
        $file = $_FILES['gambar'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (!in_array($file['type'], $allowed)) {
            $message = 'ERROR:Format file tidak didukung. Gunakan JPG, PNG, WebP, atau GIF.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $message = 'ERROR:Ukuran file maksimal 5MB.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('gallery_') . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                $stmt = $db->prepare('INSERT INTO gallery (gambar, caption, urutan) VALUES (?, ?, ?)');
                $stmt->execute([$filename, trim($_POST['caption'] ?? ''), (int)($_POST['urutan'] ?? 0)]);
                $message = 'Foto berhasil diupload!';
            } else {
                $message = 'ERROR:Gagal mengupload file.';
            }
        }
    } elseif ($action === 'delete') {
        $stmt = $db->prepare('SELECT gambar FROM gallery WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        $row = $stmt->fetch();
        if ($row) {
            @unlink($upload_dir . $row['gambar']);
            $stmt = $db->prepare('DELETE FROM gallery WHERE id = ?');
            $stmt->execute([(int)$_POST['id']]);
            $message = 'Foto berhasil dihapus!';
        }
    }
}

$items = $db->query('SELECT * FROM gallery ORDER BY urutan ASC')->fetchAll();
?>

<div class="admin-header">
    <h1>Kelola Gallery</h1>
</div>

<?php if ($message): ?>
<div class="admin-alert <?= str_starts_with($message, 'ERROR:') ? 'error' : 'success' ?>">
    <?= e(str_starts_with($message, 'ERROR:') ? substr($message, 6) : $message) ?>
</div>
<?php endif; ?>

<!-- Upload Form -->
<form method="POST" enctype="multipart/form-data" class="admin-form" style="margin-bottom: 30px;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="upload">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Upload Foto Baru</h3>

    <div class="form-group">
        <label>Pilih Gambar (JPG, PNG, WebP, GIF — maks 5MB)</label>
        <input type="file" name="gambar" accept="image/*" required style="color: var(--admin-text);">
    </div>
    <div class="form-group">
        <label>Caption</label>
        <input type="text" name="caption" placeholder="Keterangan gambar">
    </div>
    <div class="form-group">
        <label>Urutan</label>
        <input type="number" name="urutan" value="<?= count($items) + 1 ?>">
    </div>

    <button type="submit" class="btn-admin">Upload</button>
</form>

<!-- Gallery Grid -->
<h3 style="color: var(--admin-accent); margin-bottom: 15px;">Foto Saat Ini (<?= count($items) ?>)</h3>

<?php if (empty($items)): ?>
<p style="color: var(--admin-text-dim);">Belum ada foto.</p>
<?php else: ?>
<div class="gallery-admin-grid">
    <?php foreach ($items as $item): ?>
    <div class="gallery-admin-item">
        <img src="../uploads/gallery/<?= e($item['gambar']) ?>" alt="<?= e($item['caption']) ?>">
        <p><?= e($item['caption'] ?: '(tanpa caption)') ?></p>
        <form method="POST" class="inline-form" onsubmit="return confirm('Hapus foto ini?')">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <button type="submit" class="btn-admin btn-small btn-danger">Hapus</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
