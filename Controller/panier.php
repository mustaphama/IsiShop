<?php
include __DIR__ . '/../modele/Modele.php';
include __DIR__ . '/../vendor/autoload.php';

class Panier{
    public $modele;


    public function __construct(){
        $this->modele = new ModeleWeb4Shop();
    }

    public function getorders(){
        $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
            $total = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            if(isset($_POST['ajouterAuPanier'])){
                if (isset($_POST['prod']) &&  isset($_POST['quantite'])){
                    $produit = $_POST['prod'];
                    $quantite = $_POST['quantite'];
                    $requete =$this->modele->connexion->query("select * from products where products.id=$produit");
                    $prod = $requete->fetchAll(PDO::FETCH_ASSOC);
                    $panier[$produit]=[
                        'id' => $prod[0]['id'],
                        'nom' => $prod[0]['name'],
                        'prix' => $prod[0]['price'],
                        'quantite' => $quantite,
                        'img' => $prod[0]['image'],
        
                    ];
                    $total = $total + ($prod[0]['price'] * $quantite);
                    $_SESSION['total'] =$total;
                    $_SESSION['panier'] = $panier;
                    // if ($_SESSION['connexion']==true){
        
                    //     $insert = $this->modele->connexion->prepare("insert into orders");
                    //     $insert->execute();
                    // }
                }
            }
        }
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            if(isset($_POST['supprime'])){
                foreach ($_SESSION['panier'] as $cle => $produit) {
                    if ($produit['id']===$_POST['p']) {
                        $_SESSION['total']= $_SESSION['total'] - $produit['quantite'] * $produit['prix'];
                        unset($_SESSION['panier'][$cle]);
                        break; 
                    }
                }
            }
            header("Location: orderitems.php");
            exit();
        }
        
        // $requete =$this->modele->connexion->query("select * from orderitems,products,orders where products.id=orderitems.product_id and 
        // customer_id = 1 and orders.id = orderitems.order_id and (orders.status = 0 or orders.status = 1)");
        $categories = $this->modele->importerTable("categories");
        // $panier = $requete->fetchAll(PDO::FETCH_ASSOC);
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('panier.html.twig', ['panier'=> $panier ,'categories' => $categories, 'total'=> $total]);

    }

    // public function enleve_order(){
        
           

    // }
}

