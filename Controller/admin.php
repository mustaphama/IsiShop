<?php
require_once __DIR__ . '/../modele/Modele.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Admin {
    public $modele;

    public function __construct() {
        $this->modele = new ModeleWeb4Shop();
    }

    public function adminPage() {
        if (isset($_POST['order'])){
            $orderid = $_POST['order'];
            $customerid = $_POST['customer_id'];
            $delivery = $_POST['delivery_add'];
            $session_id = session_id();
            $this->modele->ChangeStatus($orderid);
            //on crée une nouvelle commande vu que l'autre est passé
            $this->modele->create_commande($customerid,$delivery,$session_id);
        }
        $orders = $this->modele->import_commande();
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('orderscheck.html.twig', ['orders' => $orders]);
    }
}
$admin = new admin();
$admin->adminPage();