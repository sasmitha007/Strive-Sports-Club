<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/sessions.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../models/user.php';

class AuthController
{
    private UserModel $users;

    public function __construct()
    {
        // Use the shared DB connection via the model factory
        $this->users = UserModel::fromDefault();
    }

    public function register(array $data): void
    {
        $fullname = trim(($data['first_name'] ?? '')) . ' ' . trim(($data['last_name'] ?? ''));
        $email    = trim(($data['email'] ?? ''));
        $contact  = trim(($data['contact'] ?? ''));
        $password = $data['password'] ?? '';
        $confirm  = $data['password_confirm'] ?? '';

        if ($password !== $confirm) {
            $_SESSION['register_error'] = 'Passwords do not match.';
            header('Location: ../pages/register.php');
            exit();
        }

        try {
            if ($this->users->isEmailTaken($email)) {
                $_SESSION['register_error'] = 'Email already exists.';
                header('Location: ../pages/register.php');
                exit();
            }

            $this->users->create($fullname, $email, $contact, $password, 3);

            $_SESSION['register_success'] = true;
            header('Location: ../pages/register.php');
            exit();
        } catch (Throwable $e) {
            $_SESSION['register_error'] = 'Database error: ' . $e->getMessage();
            header('Location: ../pages/register.php');
            exit();
        }
    }

    public function login(string $email, string $password): void
    {
        try {
            $user = $this->users->verifyLogin($email, $password);
            if ($user) {
                // 1) Rotate the session ID *after* verifying credentials,
                //    *before* populating $_SESSION to prevent fixation.
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                session_regenerate_id(true);

                // 2) Store the logged-in user
                $_SESSION['user'] = [
                    'id'       => (int)$user['id'],
                    'fullname' => $user['full_name'] ?? '',
                    'email'    => $user['email'] ?? '',
                    'role_id'  => (int)($user['role_id'] ?? 3),
                ];

                // (optional) add a CSRF token for forms
                // $_SESSION['csrf'] = bin2hex(random_bytes(32));

                // 3) Release the session lock so the cookie + data are flushed
                //    and other requests aren't blocked.
                session_write_close();

                $this->users->touchLastLogin((int)$user['id']);

                $this->redirectByRole((int)$user['role_id']);
                return;
            }
        } catch (Throwable $e) {
            // fall through
        }

        // On failure, set a flag and redirect
        $_SESSION['login_error'] = true;
        session_write_close();
        header('Location: ../pages/login.php');
        exit();
    }


    public function logout(): void
    {
        logoutUser();
    }

    private function redirectByRole(int $roleId): void
    {
        if ($roleId === 1) {
            header('Location: ../pages/admin/admindash.php');
        } elseif ($roleId === 2) {
            header('Location: ../pages/coach/coachdash.php');
        } else {
            header('Location: ../pages/dashboard.php');
        }
        exit();
    }
}

// --- Routing section (keeps same behaviour) ---
$auth = new AuthController();

if (isset($_POST['register'])) {
    $auth->register($_POST);
} elseif (isset($_POST['login'])) {
    $auth->login($_POST['email'] ?? '', $_POST['password'] ?? '');
} elseif (isset($_GET['logout'])) {
    $auth->logout();
}
