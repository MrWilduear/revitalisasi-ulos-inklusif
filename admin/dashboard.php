<?php require_once __DIR__ . '/header.php'; ?>
<?php
$db = get_db();

$reg_count = $db->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
$sponsor_count = $db->query('SELECT COUNT(*) FROM sponsors')->fetchColumn();
$agenda_count = $db->query('SELECT COUNT(*) FROM agenda')->fetchColumn();
$gallery_count = $db->query('SELECT COUNT(*) FROM gallery')->fetchColumn();
?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <span>Selamat datang, <?= e($_SESSION['admin_username']) ?></span>
</div>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-value"><?= $reg_count ?></div>
        <div class="stat-label">Pendaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $sponsor_count ?></div>
        <div class="stat-label">Sponsor</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $agenda_count ?></div>
        <div class="stat-label">Agenda</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $gallery_count ?></div>
        <div class="stat-label">Foto Gallery</div>
    </div>
</div>

<h2 style="color: var(--admin-accent); margin-bottom: 15px;">Pendaftar Terbaru</h2>
<?php
$stmt = $db->query('SELECT * FROM registrations ORDER BY created_at DESC LIMIT 5');
$recent = $stmt->fetchAll();
?>
<?php if (empty($recent)): ?>
<p style="color: var(--admin-text-dim);">Belum ada pendaftar.</p>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr><th>Nama</th><th>Email</th><th>Instansi</th><th>Tanggal</th></tr>
    </thead>
    <tbody>
        <?php foreach ($recent as $r): ?>
        <tr>
            <td><?= e($r['nama']) ?></td>
            <td><?= e($r['email']) ?></td>
            <td><?= e($r['instansi']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
