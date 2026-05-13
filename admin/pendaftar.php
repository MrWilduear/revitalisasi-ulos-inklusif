<?php require_once __DIR__ . '/header.php'; ?>
<?php
$db = get_db();

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pendaftar_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nama', 'Email', 'Telepon', 'Instansi', 'Pesan', 'Tanggal']);
    $stmt = $db->query('SELECT * FROM registrations ORDER BY created_at DESC');
    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['nama'], $row['email'], $row['telepon'],
            $row['instansi'], $row['pesan'],
            date('d/m/Y H:i', strtotime($row['created_at']))
        ]);
    }
    fclose($output);
    exit;
}

// Delete
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    if (($_POST['action'] ?? '') === 'delete') {
        $stmt = $db->prepare('DELETE FROM registrations WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        $message = 'Pendaftar berhasil dihapus.';
    }
}

$items = $db->query('SELECT * FROM registrations ORDER BY created_at DESC')->fetchAll();
?>

<div class="admin-header">
    <h1>Daftar Pendaftar (<?= count($items) ?>)</h1>
    <a href="?export=csv" class="btn-admin">Export CSV</a>
</div>

<?php if ($message): ?>
<div class="admin-alert success"><?= e($message) ?></div>
<?php endif; ?>

<?php if (empty($items)): ?>
<p style="color: var(--admin-text-dim);">Belum ada pendaftar.</p>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Instansi</th><th>Pesan</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['nama']) ?></td>
            <td><?= e($item['email']) ?></td>
            <td><?= e($item['telepon'] ?: '-') ?></td>
            <td><?= e($item['instansi'] ?: '-') ?></td>
            <td><?= e(mb_strimwidth($item['pesan'] ?: '-', 0, 40, '...')) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
            <td>
                <form method="POST" class="inline-form" onsubmit="return confirm('Hapus pendaftar ini?')">
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
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
