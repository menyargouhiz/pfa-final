<?php

class Comment
{
    public $id;
    public $review_id;
    public $user_id;
    public $author;
    public $text;
    public $created_at;

    public function __construct($review_id = "", $user_id = null, $author = "", $text = "", $created_at = "")
    {
        if ($review_id !== "") $this->review_id = $review_id;
        $this->user_id = $user_id;
        if ($author !== "") $this->author = $author;
        if ($text !== "") $this->text = $text;
        if ($created_at !== "") $this->created_at = $created_at;
    }

    public static function create($review_id, $user_id, $author, $text) {
        global $cnx;
        $stmt = $cnx->prepare("INSERT INTO comments (review_id, user_id, author, text) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$review_id, $user_id, $author, $text]);
    }

    public static function findByReview($review_id) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM comments WHERE review_id = ? ORDER BY created_at ASC");
        $stmt->execute([$review_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        return $stmt->fetchAll();
    }

    public static function delete($id) {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
