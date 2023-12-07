<?php
class ModeleWeb4Shop {
    private $connexion;
    public function __construct($serveur, $baseDeDonnees) {
        try {
            $this->connexion = new PDO("mysql:host=$serveur;dbname=$baseDeDonnees", "root", null);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Échec de la connexion à la base de données : " . $e->getMessage());
        }
    }

    public function importerTableLogins() {
        $tableAImporter = "logins";
        $sql = "SELECT * FROM logins";
        try {
            $requete = $this->connexion->query($sql);

            $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);

            return $donnees;
        } catch (PDOException $e) {
            die("Échec de la requête SQL : " . $e->getMessage());
        }
    }
    public function fermerConnexion() {
        $this->connexion = null;
    }
}