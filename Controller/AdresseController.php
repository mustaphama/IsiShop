<?php
require_once(__DIR__ . '/../Modele/Modele.php');
require_once(__DIR__ . '/../vendor/autoload.php');
class AdresseController {
    private $modele;
    public function importerDonneeUtilisateur($customerUsername) {
        $this->modele = new ModeleWeb4Shop();
        $donnees = $this->modele->UsersAdressData($customerUsername);
        $this->modele->fermerConnexion();
        return $donnees;
    }
    public function AjoutUtilisateurNonConnecté($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email) {
        $this->modele = new ModeleWeb4Shop();
        $customerid = $this->modele->createUnregistredUser($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email);
        $this->modele->fermerConnexion();
        return $customerid;
    }
    public function AjoutAdresseLivraison($firstname, $lastname, $add1, $add2, $add3, $postcode, $phone, $email) {
        $this->modele = new ModeleWeb4Shop();
        $id = $this->modele->createUnregistredUser($firstname, $lastname, $add1, $add2, $add3, $postcode, $phone, $email);
        $this->modele->fermerConnexion();
        return $id;
    }
    public function AjoutCommandesNonEnregistré($customerId, $delivery_add, $status) {
        $this->modele = new ModeleWeb4Shop();
        $id = $this->modele->addOrdersUnregistred($customerId, $delivery_add,$status);
        $this->modele->fermerConnexion();
        return $id;
    }
    public function AjoutAdresseCommande($orderId, $delivery_add) {
        $this->modele = new ModeleWeb4Shop();
        $this->modele->addAdresstoOrder($orderId, $delivery_add);
        $this->modele->fermerConnexion();
    }
    public function RemplirPanier($orderId, $product_id, $quantity) {
        $this->modele = new ModeleWeb4Shop();
        $id = $this->modele->fillChart($orderId, $product_id, $quantity);
        $this->modele->fermerConnexion();
        return $id;
    }
}
$controller = new AdresseController();
if (isset($_SESSION['client']['username'])){
$customerUsername=$_SESSION['client']['username'];
$donnees = $controller->importerDonneeUtilisateur($customerUsername);
$data=[
    'adresse1'=>$donnees[0]['add1'],
    'adresse2'=>$donnees[0]['add2'],
    'adresse3'=>$donnees[0]['add3'],
    'adressePostale'=>$donnees[0]['postcode'],
    'nom' => $donnees[0]['surname'],
    'prenom' => $donnees[0]['forname'],
    'email' => $donnees[0]['email'],
    'phone' => $donnees[0]['phone'],
];}
else {
    $data=[];
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $radioname = isset($_POST["radioname"]) ? $_POST["radioname"] : "";
    if ($radioname === "NouvelleAdresse") {
        $adresse1 = isset($_POST["NouvelleAdresse1"]) ? $_POST["NouvelleAdresse1"] : "";
        $adresse2 = isset($_POST["NouvelleAdresse2"]) ? $_POST["NouvelleAdresse2"] : "";
        $adresse3 = isset($_POST["NouvelleAdresse3"]) ? $_POST["NouvelleAdresse3"] : "";
        $nom = isset($_POST["NouveauNom"]) ? $_POST["NouveauNom"] : "";
        $prenom = isset($_POST["NouveauPrenom"]) ? $_POST["NouveauPrenom"] : "";
        $email = isset($_POST["NouveauEmail"]) ? $_POST["NouveauEmail"] : "";
        $phone = isset($_POST["NouveauTel"]) ? $_POST["NouveauTel"] : "";
        $adressePostale = isset($_POST["NouvelleAdressePostale"]) ? $_POST["NouvelleAdressePostale"] : "";
        $data = [
            'adresse1' => $adresse1,
            'adresse2' => $adresse2,
            'adresse3' => $adresse3,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'phone' => $phone,
            'adressePostale' => $adressePostale
        ];
        foreach ($data as $key => $value) {
            if ($key !== 'adresse2' && empty($value)) {
                header("Location: AdresseController.php");
                exit;
            }
        }
    }
    if (isset($_SESSION['client']['username'])){
        $deliveryid = $controller->AjoutUtilisateurNonConnecté($prenom, $nom, $adresse1, $adresse2, $adresse3, $adressePostale, $phone, $email);
        if (isset($_SESSION['client']['orderid'])){
            $orderid = $_SESSION['client']['orderid'];
            $controller->AjoutAdresseCommande($orderid, $deliveryid);
        }
    }
    else{
        $customerid = $controller->AjoutUtilisateurNonConnecté($prenom, $nom, $adresse1, $adresse2, $adresse3, $adressePostale, $phone, $email);
        $deliveryid = $controller->AjoutUtilisateurNonConnecté($prenom, $nom, $adresse1, $adresse2, $adresse3, $adressePostale, $phone, $email);
        $orderid = $controller->AjoutCommandesNonEnregistré($customerid,$deliveryid,1);
        $_SESSION['client']['orderid'] = $orderid;
        if(isset($_SESSION['panier'])){
            foreach ($_SESSION['panier'] as $produit){
                $controller->RemplirPanier($orderid,$produit['id'],$produit['quantity']);
            }
        }
    }
    
    header("Location: PagePaiement.php");
    exit;
}
else{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('ChoixAdresse.html.twig',['data'=>$data]);
}