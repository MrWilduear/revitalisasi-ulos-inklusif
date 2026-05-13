<?php require_once __DIR__ . '/header.php'; ?>
<?php
$db = get_db();
$message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $stmt = $db->prepare('INSERT INTO agenda (waktu, judul, deskripsi, urutan) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            trim($_POST['waktu']),
            trim($_POST['judul']),
            trim($_POST['deskripsi']),
            (int)($_POST['urutan'] ?? 0),
        ]);
        $message = 'Agenda berhasil ditambahkan!';
    } elseif ($action === 'edit') {
        $stmt = $db->prepare('UPDATE agenda SET waktu = ?, judul = ?, deskripsi = ?, urutan = ? WHERE id = ?');
        $stmt->execute([
            trim($_POST['waktu']),
            trim($_POST['judul']),
            trim($_POST['deskripsi']),
            (int)($_POST['urutan'] ?? 0),
            (int)$_POST['id'],
        ]);
        $message = 'Agenda berhasil diperbarui!';
    } elseif ($action === 'delete') {
        $stmt = $db->prepare('DELETE FROM agenda WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        $message = 'Agenda berhasil dihapus!';
    }
}

// Load items
$items = $db->query('SELECT * FROM agenda ORDER BY urutan ASC')->fetchAll();

// Edit mode?
$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM agenda WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit_item = $stmt->fetch();
}
?>

<div class="admin-header">
    <h1>Kelola Agenda</h1>
</div>

<?php if ($message): ?>
<div class="admin-alert success"><?= e($message) ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<form method="POST" class="admin-form" style="margin-bottom: 30px;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'add' ?>">
    <?php if ($edit_item): ?>
    <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
    <?php endif; ?>

    <h3 style="color: var(--admin-accent); margin-bottom: 15px;">
        <?= $edit_item ? 'Edit Agenda' : 'Tambah Agenda Baru' ?>
    </h3>

    <div class="form-group">
        <label>Waktu</label>
        <input type="text" name="waktu" value="<?= e($edit_item['waktu'] ?? '') ?>" placeholder="08:00 - 09:00" required>
    </div>
    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" value="<?= e($edit_item['judul'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" style="height:80px"><?= e($edit_item['deskripsi'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Urutan</label>
        <input type="number" name="urutan" value="<?= e($edit_item['urutan'] ?? count($items) + 1) ?>">
    </div>

    <button type="submit" class="btn-admin"><?= $edit_item ? 'Update' : 'Tambah' ?></button>
    <?php if ($edit_item): ?>
    <a href="agenda.php" class="btn-admin" style="background: #555; margin-left:10px; text-decoration:none; display:inline-block; color:#fff;">Batal</a>
    <?php endif; ?>
</form>

<!-- List -->
<table class="admin-table">
    <thead>
        <tr><th>Urutan</th><th>Waktu</th><th>Judul</th><th>Deskripsi</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['urutan'] ?></td>
            <td><?= e($item['waktu']) ?></td>
            <td><?= e($item['judul']) ?></td>
            <td><?= e(mb_strimwidth($item['deskripsi'], 0, 50, '...')) ?></td>
            <td class="actions">
                <a href="?edit=<?= $item['id'] ?>" class="btn-admin btn-small">Edit</a>
                <form method="POST" class="inline-form" onsubmit="return confirm('Hapus agenda ini?')">
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
