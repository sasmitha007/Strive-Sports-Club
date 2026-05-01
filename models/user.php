<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/db.php'; // exposes Database::connect()

class UserModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** Convenience factory that uses your core/db.php connection */
    public static function fromDefault(): self
    {
        return new self(Database::connect());
    }

    /** Get a user by ID (without password hash) */
    public function findById(int $id): ?array
    {
        $sql = "SELECT id, full_name, email, contact, role_id, last_login FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Get a user by email (includes password for login checks) */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Check if an email is taken (optionally excluding a specific user id) */
    public function isEmailTaken(string $email, ?int $excludeUserId = null): bool
    {
        if ($excludeUserId === null) {
            $sql = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
        } else {
            $sql = "SELECT 1 FROM users WHERE email = :email AND id <> :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email, ':id' => $excludeUserId]);
        }
        return (bool)$stmt->fetchColumn();
    }

    /** Create a new user. Returns new user id. Default role_id = 3 (regular user). */
    public function create(string $fullName, string $email, string $contact, string $plainPassword, int $roleId = 3): int
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password, contact, role_id) 
                VALUES (:full_name, :email, :password, :contact, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':full_name' => $fullName,
            ':email'     => $email,
            ':password'  => $hash,
            ':contact'   => $contact,
            ':role_id'   => $roleId,
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Verify login. Returns the full user row (without password) if ok, otherwise null. */
    public function verifyLogin(string $email, string $plainPassword): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user) return null;
        if (!password_verify($plainPassword, $user['password'])) return null;

        // Don't leak hash to callers
        unset($user['password']);
        return $user;
    }

    /** Update last_login to NOW() */
    public function touchLastLogin(int $userId): bool
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $userId]);
    }

    /**
     * Update profile fields. If $newPlainPassword is provided, password is updated too.
     * Returns true on success.
     */
    public function updateProfile(int $userId, string $fullName, string $email, string $contact, ?string $newPlainPassword = null): bool
    {
        // Build dynamic SQL depending on password update
        if ($newPlainPassword === null || $newPlainPassword === '') {
            $sql = "UPDATE users 
                    SET full_name = :full_name, email = :email, contact = :contact 
                    WHERE id = :id";
            $params = [
                ':full_name' => $fullName,
                ':email'     => $email,
                ':contact'   => $contact,
                ':id'        => $userId,
            ];
        } else {
            $hash = password_hash($newPlainPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users 
                    SET full_name = :full_name, email = :email, contact = :contact, password = :password 
                    WHERE id = :id";
            $params = [
                ':full_name' => $fullName,
                ':email'     => $email,
                ':contact'   => $contact,
                ':password'  => $hash,
                ':id'        => $userId,
            ];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}

