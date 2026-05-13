<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!verify_csrf()) {
    echo json_encode(['success' => false, 'message' => 'Token tidak valid. Silakan muat ulang halaman.']);
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$instansi = trim($_POST['instansi'] ?? '');
$pesan = trim($_POST['pesan'] ?? '');

if ($nama === '' || $email === '') {
    echo json_encode(['success' => false, 'message' => 'Nama dan email wajib diisi.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
    exit;
}

try {
    $db = get_db();
    $stmt = $db->prepare('INSERT INTO registrations (nama, email, telepon, instansi, pesan) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$nama, $email, $telepon, $instansi, $pesan]);
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil! Kami akan menghubungi Anda.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
}
