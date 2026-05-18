<?php

class User
{
    public $id;
    public $nom;
    public $email;
    public $password;

    public function __construct($nom = "", $email = "", $password = "")
    {
        if ($nom !== "") $this->nom = $nom;
        if ($email !== "") $this->email = $email;
        if ($password !== "") $this->password = $password;
    }

    public static function create($nom, $email, $password) {
        global $cnx;
        $stmt = $cnx->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$nom, $email, $hashed]);
    }

    public static function readAll() {
        global $cnx;
        $stmt = $cnx->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    public static function findById($id) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    public static function findByEmail($email) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    public static function update($id, $nom, $email) {
        global $cnx;
        $stmt = $cnx->prepare("UPDATE users SET nom = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nom, $email, $id]);
    }

    public static function delete($id) {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>