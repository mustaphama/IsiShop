<?php
require __DIR__ . '\produits.php';
class LoginController {
    private $modele;
    public function __construct() {
        $this->modele = new ModeleWeb4Shop();
    }
    public function importerDonneeLogins() {
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
            $_SESSION['connexion']=true;
            break;
        }
    }
    if ($Validation==True){
        $produit = new Produit();
        $produit->import_products();
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