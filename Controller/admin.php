<?php
include __DIR__ . '/../modele/Modele.php';
include __DIR__ . '/../vendor/autoload.php';

class Admin {
    public $modele;

    public function __construct() {
        $this->modele = new ModeleWeb4Shop();
    }

    public function adminPage() {
        if (isset($_POST['order'])){
            $orderid = $_POST['order'];
            $this->modele->ChangeStatus($orderid);
        }
        $requete = $this->modele->connexion->query(" select * from orders where status=2 union select * from orders where status=10");
        $orders = $requete->fetchAll(PDO::FETCH_ASSOC);
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('orderscheck.html.twig', ['orders' => $orders]);
    }
}
$admin = new admin();
$admin->adminPage();