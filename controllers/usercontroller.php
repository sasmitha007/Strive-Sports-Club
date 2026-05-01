<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/sessions.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/Feedback.php';

requireLogin();

$users    = UserModel::fromDefault();
$feedback = FeedbackModel::fromDefault();

/* -------------------- handle profile update -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $userId = (int)($_SESSION['user']['id'] ?? 0);

    $first   = trim($_POST['first_name'] ?? '');
    $last    = trim($_POST['last_name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $pass2   = $_POST['password_confirm'] ?? '';

    if ($first === '' || $last === '' || $email === '') {
        $_SESSION['profile_error'] = 'First name, last name, and email are required.';
        header('Location: ../pages/user/profile.php');
        exit();
    }

    $full_name = $first . ' ' . $last;

    // Check if email is used by someone else
    if ($users->isEmailTaken($email, $userId)) {
        $_SESSION['profile_error'] = 'That email is already taken.';
        header('Location: ../pages/user/profile.php');
        exit();
    }

    // Optional password updates
    $newPassword = null;
    if ($pass !== '' || $pass2 !== '') {
        if ($pass !== $pass2) {
            $_SESSION['profile_error'] = 'Passwords do not match.';
            header('Location: ../pages/user/profile.php');
            exit();
        }
        if (strlen($pass) < 6) {
            $_SESSION['profile_error'] = 'Password must be at least 6 characters.';
            header('Location: ../pages/user/profile.php');
            exit();
        }
        $newPassword = $pass;
    }

    try {
        $users->updateProfile($userId, $full_name, $email, $contact, $newPassword);

        // Keep session user fresh for header/UI
        $_SESSION['user']['fullname'] = $full_name;
        $_SESSION['user']['email']    = $email;

        $_SESSION['profile_success'] = 'Profile updated successfully.';
        header('Location: ../pages/user/profile.php');
        exit();
    } catch (Throwable $e) {
        $_SESSION['profile_error'] = 'Database error while updating profile.';
        header('Location: ../pages/user/profile.php');
        exit();
    }
}
/* ------------------ END: handle profile update ------------------ */

/* -------------------- handle feedback submit -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $userId = (int)($_SESSION['user']['id'] ?? 0);

    $message = trim($_POST['message'] ?? '');
    $type    = strtolower(trim($_POST['target_type'] ?? ''));
    $rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    // target_id is optional; allow empty -> NULL; otherwise force >= 1
    $targetIdRaw = $_POST['target_id'] ?? '';
    $targetId    = ($targetIdRaw === '' ? null : max(1, (int)$targetIdRaw));

    // Basic validation (controller = UX rules; model = persistence only)
    $allowedTypes = ['coach','sport','session','other'];
    if ($message === '') {
        $_SESSION['feedback_error'] = 'Message is required.';
        header('Location: ../pages/user/feedback.php');
        exit();
    }
    if (!in_array($type, $allowedTypes, true)) {
        $_SESSION['feedback_error'] = 'Invalid target type.';
        header('Location: ../pages/user/feedback.php');
        exit();
    }
    if ($rating < 1 || $rating > 5) {
        $_SESSION['feedback_error'] = 'Rating must be between 1 and 5.';
        header('Location: ../pages/user/feedback.php');
        exit();
    }

    try {
        $feedback->create($userId, $message, $type, $targetId, $rating);
        $_SESSION['feedback_success'] = 'Thanks! Your feedback was submitted.';
        header('Location: ../pages/user/feedback.php');
        exit();
    } catch (Throwable $e) {
        $_SESSION['feedback_error'] = 'Database error while saving feedback.';
        header('Location: ../pages/user/feedback.php');
        exit();
    }
}
/* ------------------ END: handle feedback submit ------------------ */

// Fallback
header('Location: ../pages/dashboard.php');
exit();