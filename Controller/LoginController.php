<?php
require __DIR__ . '\produits.php';
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
    foreach ($donnees as $donnee) {
        if ($donnee['username'] == $username && (password_verify($password, $donnee['password']))) {
            $Validation = True;
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['connexion']=true;
            $requete=$login->modele->connexion->prepare("SELECT o.id , o.total FROM orders o join logins l on o.customer_id = l.customer_id  where username = :username");
            $requete->bindParam(':username',$username);
            $requete->execute();
            $info = $requete->fetchAll(PDO::FETCH_ASSOC);
            
            $orderid = $info[0]['id'];
            $requete2 = $login->modele->connexion->query("SELECT p.id , p.name , p.price,o.quantity,p.image  FROM orderitems o join products p on o.product_id=p.id  where order_id = $orderid");
            $commandes = $requete2->fetchAll(PDO::FETCH_ASSOC);
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