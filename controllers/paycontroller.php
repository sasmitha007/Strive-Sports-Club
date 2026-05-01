<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/sessions.php';
require_once __DIR__ . '/../models/pay.php';

requireLogin();
$payments = PaymentModel::fromDefault();

function redirectToDashboard(string $msg = ''): void {
  $loc = '../pages/dashboard.php';
  if ($msg !== '') $loc .= '?msg=' . urlencode($msg);
  header('Location: ' . $loc);
  exit;
}

function backToPayment(int $bookingId, string $err): void {
  $_SESSION['payment_error'] = $err;
  header('Location: ../pages/paychoice.php?booking_id=' . $bookingId);
  exit;
}

$action    = $_POST['action'] ?? '';
$bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$userId    = (int)($_SESSION['user']['id'] ?? 0);

if ($bookingId <= 0 || $userId <= 0) {
  redirectToDashboard();
}

// Check booking ownership
if (!$payments->verifyBookingOwnership($bookingId, $userId)) {
  redirectToDashboard();
}

switch ($action) {
  // ===== Pay Later =====
  case 'pay_later':
    try {
        $payments->ensureUnpaidRecord($bookingId, $userId);
        redirectToDashboard('payment_pending');
    } catch (Throwable $e) {
        backToPayment($bookingId, 'Could not save payment. Please try again.');
    }
    break;

  // ===== Pay Now (Initial trigger) =====
  case 'pay_now':
    try {
        $payments->startPayment($bookingId, $userId);
        header('Location: ../pages/payment.php?booking_id=' . $bookingId);
        exit;
    } catch (Throwable $e) {
        backToPayment($bookingId, 'Could not start payment. Please try again.');
    }
    break;

  // ===== Complete Payment =====
  case 'complete_payment':
    $method = trim($_POST['method'] ?? '');
    $amount = (float)($_POST['amount'] ?? 0);
    if ($method === '' || $amount <= 0) {
        backToPayment($bookingId, 'Invalid payment details.');
    }

    try {
        $payments->completePayment($bookingId, $userId, $method, $amount);
        redirectToDashboard('payment_success');
    } catch (Throwable $e) {
        backToPayment($bookingId, 'Payment failed: ' . $e->getMessage());
    }
    break;

  default:
    redirectToDashboard();
}