<?php

class Review
{
    public $id;
    public $restaurant_id;
    public $author;
    public $rating;
    public $ambiance;
    public $cleanliness;
    public $quality;
    public $service;
    public $text;
    public $facture_code;
<<<<<<< HEAD
    public $facture_verified = false;
=======
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
    public $date;
    public $user_id;

    public function __construct($restaurant_id = "", $author = "", $rating = 0, $text = "", $date = "", $user_id = null, $ambiance = 0, $cleanliness = 0, $quality = 0, $service = 0, $facture_code = "")
    {
        if ($restaurant_id !== "") $this->restaurant_id = $restaurant_id;
        if ($author !== "") $this->author = $author;
        if ($rating !== 0) $this->rating = $rating;
        if ($text !== "") $this->text = $text;
        if ($facture_code !== "") $this->facture_code = $facture_code;
        if ($date !== "") $this->date = $date;
        $this->user_id = $user_id;
        $this->ambiance = $ambiance;
        $this->cleanliness = $cleanliness;
        $this->quality = $quality;
        $this->service = $service;
    }

    public static function create($restaurant_id, $author, $rating, $text, $facture_code, $ambiance = 0, $cleanliness = 0, $quality = 0, $service = 0, $user_id = null) {
        global $cnx;
        $date = date('Y-m-d');
        $stmt = $cnx->prepare("INSERT INTO reviews (restaurant_id, author, rating, ambiance, cleanliness, quality, service, text, facture_code, date, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$restaurant_id, $author, $rating, $ambiance, $cleanliness, $quality, $service, $text, $facture_code, $date, $user_id]);
    }

    public static function findByRestaurant($restaurant_id) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM reviews WHERE restaurant_id = ? ORDER BY date DESC");
        $stmt->execute([$restaurant_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Review');
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Review');
        return $stmt->fetch();
    }

    public static function update($id, $rating, $text) {
        global $cnx;
        $stmt = $cnx->prepare("UPDATE reviews SET rating = ?, text = ? WHERE id = ?");
        return $stmt->execute([$rating, $text, $id]);
    }

    public static function delete($id) {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
