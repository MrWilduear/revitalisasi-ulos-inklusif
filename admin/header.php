<?php require_once __DIR__ . '/auth.php'; ?>
<?php require_once __DIR__ . '/../includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Revitalisasi Ulos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <img src="../logo/logo.png" alt="Logo">
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
            <li><a href="konten.php" <?= basename($_SERVER['PHP_SELF']) === 'konten.php' ? 'class="active"' : '' ?>>Konten</a></li>
            <li><a href="agenda.php" <?= basename($_SERVER['PHP_SELF']) === 'agenda.php' ? 'class="active"' : '' ?>>Agenda</a></li>
            <li><a href="gallery.php" <?= basename($_SERVER['PHP_SELF']) === 'gallery.php' ? 'class="active"' : '' ?>>Gallery</a></li>
            <li><a href="sponsors.php" <?= basename($_SERVER['PHP_SELF']) === 'sponsors.php' ? 'class="active"' : '' ?>>Sponsor</a></li>
            <li><a href="pendaftar.php" <?= basename($_SERVER['PHP_SELF']) === 'pendaftar.php' ? 'class="active"' : '' ?>>Pendaftar</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>
    <main class="admin-main">
