<?php
require_once(__DIR__ . '/../Modele/Modele.php');
require_once(__DIR__ . '/../vendor/autoload.php');
class LoginController {
    private $modele;
    public function importerDonneeLogins() {
        $this->modele = new ModeleWeb4Shop();
        $donneesLogins = $this->modele->importerTable("logins");
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
        if ($donnee['username'] == $username && (password_verify($password, $donnee['password']))) {
            $Validation = True;
            break;
        }
    }
    if ($Validation==True){
        header("allproducts.php");
        exit();
    } else{
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('PageConec.html.twig',['erreur'=>true]);
    }
}
else{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('PageConec.html.twig',['erreur'=>false]);
}