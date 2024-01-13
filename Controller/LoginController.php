<?php
require_once __DIR__ . '\produits.php';
class LoginController {
    public $modele;

    public function __construct(){
        $this->modele = new ModeleWeb4Shop;
    }
    public function importerDonneeLogins() {
        $donneesLogins = $this->modele->importerTable("logins");
        $this->modele->fermerConnexion();
        return $donneesLogins;
    }
}
$Validation = False;
$login = new LoginController();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $controller = new LoginController();
    $donnees = $controller->importerDonneeLogins();
    $hashedEnteredPassword = hash('sha1', $password);
    foreach ($donnees as $donnee) {
        if ($donnee['username'] == $username && ($donnee['password']===$hashedEnteredPassword)) {
            $Validation = True;
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['connexion']=true;
            $info = $login->modele->import_info_commande($username);
            
            $orderid = $info[0]['id'];
            $commandes = $login->modele->recuperer_commande($orderid);
            foreach($commandes as $command){
                $_SESSION['panier'][$command['id']] =$command;
            }
            $_SESSION['total']= $info[0]['total']; 
            $_SESSION['client']=[
                'username' => $username,
                'orderid' => $orderid
            ];
            $login->modele->fermerConnexion();
            
            break;
        }
    }
    if ($Validation==False){
        $donnees = $login->modele->importerTable("admin");
        $login->modele->fermerConnexion();
        if ($donnees[0]['username'] == $username && ($donnees[0]['password']===$hashedEnteredPassword)) {
            session_unset();
            session_destroy();
            header("Location: admin.php");
            exit();
        }

    }
    if ($Validation==True){
        header("Location: allproducts.php");
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