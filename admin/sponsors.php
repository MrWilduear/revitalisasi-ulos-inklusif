<?php require_once __DIR__ . '/header.php'; ?>
<?php
$db = get_db();
$message = '';
$upload_dir = __DIR__ . '/../uploads/sponsors/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' && isset($_FILES['logo'])) {
        $file = $_FILES['logo'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

        if (!in_array($file['type'], $allowed)) {
            $message = 'ERROR:Format file tidak didukung.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $message = 'ERROR:Ukuran file maksimal 2MB.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('sponsor_') . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                $stmt = $db->prepare('INSERT INTO sponsors (nama, logo, website, tier, urutan) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([
                    trim($_POST['nama']),
                    $filename,
                    trim($_POST['website'] ?? ''),
                    $_POST['tier'] ?? 'silver',
                    (int)($_POST['urutan'] ?? 0),
                ]);
                $message = 'Sponsor berhasil ditambahkan!';
            } else {
                $message = 'ERROR:Gagal mengupload logo.';
            }
        }
    } elseif ($action === 'delete') {
        $stmt = $db->prepare('SELECT logo FROM sponsors WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        $row = $stmt->fetch();
        if ($row) {
            @unlink($upload_dir . $row['logo']);
            $stmt = $db->prepare('DELETE FROM sponsors WHERE id = ?');
            $stmt->execute([(int)$_POST['id']]);
            $message = 'Sponsor berhasil dihapus!';
        }
    }
}

$items = $db->query('SELECT * FROM sponsors ORDER BY FIELD(tier, "platinum", "gold", "silver", "bronze"), urutan ASC')->fetchAll();
?>

<div class="admin-header">
    <h1>Kelola Sponsor</h1>
</div>

<?php if ($message): ?>
<div class="admin-alert <?= str_starts_with($message, 'ERROR:') ? 'error' : 'success' ?>">
    <?= e(str_starts_with($message, 'ERROR:') ? substr($message, 6) : $message) ?>
</div>
<?php endif; ?>

<!-- Add Form -->
<form method="POST" enctype="multipart/form-data" class="admin-form" style="margin-bottom: 30px;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add">

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">Tambah Sponsor Baru</h3>

    <div class="form-group">
        <label>Nama Sponsor *</label>
        <input type="text" name="nama" required>
    </div>
    <div class="form-group">
        <label>Logo (JPG, PNG, WebP, SVG — maks 2MB) *</label>
        <input type="file" name="logo" accept="image/*" required style="color: var(--admin-text);">
    </div>
    <div class="form-group">
        <label>Website URL</label>
        <input type="url" name="website" placeholder="https://...">
    </div>
    <div class="form-group">
        <label>Tier</label>
        <select name="tier">
            <option value="platinum">Platinum</option>
            <option value="gold">Gold</option>
            <option value="silver" selected>Silver</option>
            <option value="bronze">Bronze</option>
        </select>
    </div>
    <div class="form-group">
        <label>Urutan</label>
        <input type="number" name="urutan" value="<?= count($items) + 1 ?>">
    </div>

    <button type="submit" class="btn-admin">Tambah Sponsor</button>
</form>

<!-- List -->
<table class="admin-table">
    <thead>
        <tr><th>Logo</th><th>Nama</th><th>Tier</th><th>Website</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><img src="../uploads/sponsors/<?= e($item['logo']) ?>" alt="" style="height:40px;"></td>
            <td><?= e($item['nama']) ?></td>
            <td style="text-transform:capitalize;"><?= e($item['tier']) ?></td>
            <td><?= $item['website'] ? '<a href="' . e($item['website']) . '" target="_blank">Link</a>' : '-' ?></td>
            <td>
                <form method="POST" class="inline-form" onsubmit="return confirm('Hapus sponsor ini?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="btn-admin btn-small btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/footer.php'; ?>
