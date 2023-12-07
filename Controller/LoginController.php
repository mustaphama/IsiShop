<?php
require_once(__DIR__ . '/../Modele/Modele.php');
class LoginController {
    private $modele;
    public function __construct() {
        $this->modele = new ModeleWeb4Shop("localhost", "web4shop");
    }
    public function importerDonneeLogins() {
        $donneesLogins = $this->modele->importerTableLogins();
        $this->modele->fermerConnexion();
        return $donneesLogins;
    }
}
$Validation = False;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $controller = new LoginController();
    $donnees = $controller->importerDonneeLogins();
    foreach ($donnees as $donnee) {
        if ($donnee['username'] == $username && $donnee['password'] == $password) {
            $Validation = True;
            break;
        }
    }
}
if ($Validation==True){
    echo "Passe";
} else{
    echo "Passe pas";
}