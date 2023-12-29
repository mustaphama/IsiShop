<?php
require_once(__DIR__ . '/../Modele/Modele.php');
require_once(__DIR__ . '/../vendor/autoload.php');
class SignupController {
    private $modele;
    public function importerDonneeLogins():array {
        $this->modele = new ModeleWeb4Shop();
        $donneesLogins = $this->modele->importerTable("logins");
        $this->modele->fermerConnexion();
        return $donneesLogins;
    }
    public function importerDonneeUtilisateurs():array {
        $this->modele = new ModeleWeb4Shop();
        $donneesUtil = $this->modele->importerTable("customers");
        $this->modele->fermerConnexion();
        return $donneesUtil;
    }
    public function nouveauUtilisateur($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email, $username, $password) {
        $this->modele = new ModeleWeb4Shop();
        $this->modele->createUser($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email, $username, $password);
        $this->modele->fermerConnexion();
    }
}
$Validation = True;
$Erreur = new ArrayObject();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $ConfirmPassword = $_POST["ConfirmPassword"];
    $email = $_POST["email"];
    $controller = new SignupController();
    $donneesLogin = $controller->importerDonneeLogins();
    $donneesCustomers = $controller->importerDonneeUtilisateurs();
    foreach ($donneesLogin as $donnee) {
        if ($donnee['username'] == $username) {
            $Erreur->append(0);
            $Validation=False;
            break;
        }
    }
    foreach ($donneesCustomers as $donnee) {
        if ($donnee['email'] == $username) {
            $Erreur->append(1);
            $Validation=False;
            break;
        }
    }
    if ($ConfirmPassword!=$password){
        $Erreur->append(2);
        $Validation=False;
    }
    if (!$Validation){
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('CreationDeCompte.html.twig',['erreur'=>true,'ErreurArray'=>$Erreur]);
    }
    else{
        $controller->nouveauUtilisateur($_POST["Prenom"],$_POST["Nom"],$_POST["Adresse1"],$_POST["Adresse2"],$_POST["Adresse3"],$_POST["Postcode"],$_POST["PhoneNumber"],$_POST["email"],$_POST["username"],$_POST["password"]);
        header("allproducts.php");
        exit();
    }
}
else{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('CreationDeCompte.html.twig',['erreur'=>false]);
}