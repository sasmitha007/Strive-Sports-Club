<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/sessions.php'; // must start the session

/* ---------- Config ---------- */
const RESET_WINDOW_MINUTES = 30;
const MIN_PASSWORD_LEN     = 8;
const MAX_FORGOT_PER_10MIN = 5;  // simple rate limit per session
$PAGES_BASE = '/Indoor%20sports%20club/pages';

/* ---------- Helpers ---------- */
function normalize_phone(string $s): string {
  $d = preg_replace('/\D+/', '', $s ?? '');
  if (preg_match('/^94(\d{9})$/', $d, $m)) return '0'.$m[1]; // +94XXXXXXXXX -> 0XXXXXXXXX
  if (preg_match('/^0\d{9}$/', $d)) return $d;               // 0XXXXXXXXX
  return $d;
}
function grant_reset_session(int $userId): void {
  session_regenerate_id(true); // mitigate fixation
  $_SESSION['pw_reset_user_id'] = $userId;
  $_SESSION['pw_reset_expires'] = time() + (RESET_WINDOW_MINUTES * 60);
  $_SESSION['pw_reset_nonce']   = bin2hex(random_bytes(16)); // CSRF for reset form
}
function reset_grant_valid(): bool {
  return !empty($_SESSION['pw_reset_user_id'])
      && !empty($_SESSION['pw_reset_expires'])
      && time() <= (int)$_SESSION['pw_reset_expires'];
}
function clear_reset_grant(): void {
  unset($_SESSION['pw_reset_user_id'], $_SESSION['pw_reset_expires'], $_SESSION['pw_reset_nonce']);
}
function bump_forgot_attempts(): bool {
  $now = time();
  $_SESSION['forgot_window_start'] = $_SESSION['forgot_window_start'] ?? $now;
  $_SESSION['forgot_attempts']     = $_SESSION['forgot_attempts']     ?? 0;

  // reset window if >10 minutes
  if ($now - (int)$_SESSION['forgot_window_start'] > 600) {
    $_SESSION['forgot_window_start'] = $now;
    $_SESSION['forgot_attempts']     = 0;
  }
  $_SESSION['forgot_attempts']++;
  return $_SESSION['forgot_attempts'] <= MAX_FORGOT_PER_10MIN;
}

/* ---------- Controller ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  /* 1) FORGOT: verify email+phone, then grant session to reset (no tokens) */
  if ($action === 'forgot_request') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');

    // Basic input checks
    if ($email === '' || $phone === '') {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Please enter both your email and phone number.';
      header("Location: {$PAGES_BASE}/forgot.php"); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Please enter a valid email address.';
      header("Location: {$PAGES_BASE}/forgot.php"); exit;
    }

    if (!bump_forgot_attempts()) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Too many attempts. Please try again in a few minutes.';
      header("Location: {$PAGES_BASE}/forgot.php"); exit;
    }

    $pdo = Database::connect();
    $st = $pdo->prepare('SELECT id, contact FROM users WHERE lower(email)=:e AND is_active=TRUE LIMIT 1');
    $st->execute([':e' => $email]);
    $user = $st->fetch(PDO::FETCH_ASSOC);

    $match = $user && normalize_phone($user['contact'] ?? '') === normalize_phone($phone);
    if (!$match) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'The email and phone number do not match our records.';
      header("Location: {$PAGES_BASE}/forgot.php"); exit;
    }

    grant_reset_session((int)$user['id']);

    // Optional audit (ignore failure)
    @$pdo->prepare('INSERT INTO audit_logs (user_id, action, action_type) VALUES (:u,:a,:t)')
         ->execute([':u'=>$user['id'], ':a'=>'Password reset granted (email+phone verified)', ':t'=>'SECURITY']);

    header("Location: {$PAGES_BASE}/reset.php", true, 303);
    exit;
  }

  /* 2) RESET: check session grant, then UPDATE users.password (bcrypt) */
  if ($action === 'reset_password') {
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password_confirm'] ?? '';
    $nonce = $_POST['nonce'] ?? '';

    if (!reset_grant_valid() || !hash_equals($_SESSION['pw_reset_nonce'] ?? '', $nonce)) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Your reset session is invalid or expired. Please try again.';
      clear_reset_grant();
      header("Location: {$PAGES_BASE}/forgot.php"); exit;
    }

    if ($pass1 === '' || $pass2 === '' || $pass1 !== $pass2 || strlen($pass1) < MIN_PASSWORD_LEN) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Invalid input. Ensure passwords match and are at least '.MIN_PASSWORD_LEN.' characters.';
      header("Location: {$PAGES_BASE}/reset.php"); exit;
    }

    $userId  = (int)($_SESSION['pw_reset_user_id'] ?? 0);
    $pdo     = Database::connect();
    $newHash = password_hash($pass1, PASSWORD_BCRYPT, ['cost' => 12]);

    try {
      $pdo->prepare('UPDATE users SET password = :p WHERE id = :u')
          ->execute([':p' => $newHash, ':u' => $userId]);

      // Optional audit (ignore failure)
      @$pdo->prepare('INSERT INTO audit_logs (user_id, action, action_type) VALUES (:u,:a,:t)')
           ->execute([':u'=>$userId, ':a'=>'Password reset completed (session flow)', ':t'=>'SECURITY']);

      $_SESSION['forgot_success'] = true;
      $_SESSION['flash_message']  = 'Your password has been updated. You can log in now.';
      clear_reset_grant();
      header("Location: {$PAGES_BASE}/login.php"); exit;
    } catch (Throwable $e) {
      $_SESSION['forgot_error'] = true;
      $_SESSION['flash_message'] = 'Could not reset password. Please try again.';
      header("Location: {$PAGES_BASE}/reset.php"); exit;
    }
  }
}