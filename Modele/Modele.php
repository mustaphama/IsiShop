<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ModeleWeb4Shop {
    public $connexion;
    public function __construct() {
        try {
            $this->connexion = new PDO("mysql:host=localhost;dbname=web4shop", "root", null);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Échec de la connexion à la base de données : " . $e->getMessage());
        }
    }

    public function importerTable($table) {
        $sql = "SELECT * FROM $table";
        try {
            $requete = $this->connexion->query($sql);

            $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);

            return $donnees;
        } catch (PDOException $e) {
            die("Échec de la requête SQL : " . $e->getMessage());
        }
    }
    public function createUser($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email, $username, $password) {
        $this->connexion->beginTransaction();
        try {
            $customerQuery = "INSERT INTO customers (forname, surname, add1, add2, add3, postcode, phone, email, registered) VALUES (:forname, :surname, :add1, :add2, :add3, :postcode, :phone, :email, 1)";
            $customerStmt = $this->connexion->prepare($customerQuery);
            $customerStmt->bindParam(':forname', $forname);
            $customerStmt->bindParam(':surname', $surname);
            $customerStmt->bindParam(':add1', $add1);
            $customerStmt->bindParam(':add2', $add2);
            $customerStmt->bindParam(':add3', $add3);
            $customerStmt->bindParam(':postcode', $postcode);
            $customerStmt->bindParam(':phone', $phone);
            $customerStmt->bindParam(':email', $email);
            $customerStmt->execute();
            $customerId = $this->connexion->lastInsertId();
            $loginQuery = "INSERT INTO logins (id, customer_id, username, password) VALUES (:id, :id, :username, :password)";
            $loginStmt = $this->connexion->prepare($loginQuery);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $loginStmt->bindParam(':id', $customerId);
            $loginStmt->bindParam(':username', $username);
            $loginStmt->bindParam(':password', $hashedPassword);
            $loginStmt->execute();
            $this->connexion->commit();

            return true;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }

    public function fermerConnexion() {
        $this->connexion = null;
    }
}