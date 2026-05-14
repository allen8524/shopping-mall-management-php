<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function admin_csrf_token() {
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function admin_csrf_input() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(admin_csrf_token(), ENT_QUOTES) . '">';
}

function admin_verify_csrf_token($token) {
    return is_string($token)
        && isset($_SESSION['admin_csrf_token'])
        && hash_equals($_SESSION['admin_csrf_token'], $token);
}

function admin_require_post_csrf($redirect = '') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !admin_verify_csrf_token($_POST['csrf_token'] ?? '')) {
        error_log('Blocked admin state change due to invalid request method or CSRF token.');
        if ($redirect !== '') {
            header('Location: ' . $redirect);
            exit;
        }

        exit('잘못된 요청입니다.');
    }
}
