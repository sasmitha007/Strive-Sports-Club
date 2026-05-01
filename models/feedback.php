<?php
/**
 * FeedbackModel — encapsulates CRUD for user feedback.
 * Columns used: id, user_id, message, target_type, target_id, rating
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/db.php'; // Database::connect()

class FeedbackModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** Factory using your shared DB connection */
    public static function fromDefault(): self
    {
        return new self(Database::connect());
    }

    /** Create a feedback entry. Returns new feedback id. */
    public function create(int $userId, string $message, string $targetType, ?int $targetId, int $rating): int
    {
        $sql = "INSERT INTO feedback (user_id, message, target_type, target_id, rating)
                VALUES (:uid, :message, :type, :target_id, :rating)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':message', $message, PDO::PARAM_STR);
        $stmt->bindValue(':type', $targetType, PDO::PARAM_STR);
        if ($targetId === null) {
            $stmt->bindValue(':target_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':target_id', $targetId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    /** All feedback from a given user (for user dashboard) */
    public function forUser(int $userId): array
    {
        $sql = "SELECT id, message, target_type, target_id, rating FROM feedback WHERE user_id = :uid ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** All feedback (useful later for admin view) */
    public function all(): array
    {
        $sql = "SELECT id, user_id, message, target_type, target_id, rating FROM feedback ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
