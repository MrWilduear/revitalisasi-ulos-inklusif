<?php
require_once __DIR__ . '/db.php';

function get_setting(string $key, string $default = ''): string {
    $db = get_db();
    $stmt = $db->prepare('SELECT section_value FROM settings WHERE section_key = ?');
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['section_value'] : $default;
}

function set_setting(string $key, string $value): void {
    $db = get_db();
    $stmt = $db->prepare('INSERT INTO settings (section_key, section_value, updated_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE section_value = VALUES(section_value), updated_at = NOW()');
    $stmt->execute([$key, $value]);
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf(): bool {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
