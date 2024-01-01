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
                    if (array_key_exists($produit,$panier)){
                        $panier[$produit]['quantity']+= $quantite;
                        if ($_SESSION['connexion']==true){
                            $this->modele->augmente_quantity($panier[$produit]['quantity'],$produit,$_SESSION['client']['orderid']);
                        }
                    }
                    else{
                        $panier[$produit]=[
                            'id' => $prod[0]['id'],
                            'name' => $prod[0]['name'],
                            'price' => $prod[0]['price'],
                            'quantity' => $quantite,
                            'image' => $prod[0]['image'],
            
                        ];
                        if ($_SESSION['connexion']==true){
            
                            $this->modele->ajout_produit($_SESSION['client']['orderid'],$produit,$quantite);
                        }
                    }
                    $total = $total + ($prod[0]['price'] * $quantite);
                    $_SESSION['total'] =$total;
                    $_SESSION['panier'] = $panier;
                }
            }
            if(isset($_POST['supprime'])){
                
                $produit_id = intval($_POST['p']);
                foreach ($_SESSION['panier'] as $cle => $produit) {
                    if ($produit['id']===$produit_id) {
                        $_SESSION['total']= $_SESSION['total'] - $produit['quantity'] * $produit['price'];
                        unset($_SESSION['panier'][$cle]);
                        break; 
                    }
                }
                if ($_SESSION['connexion']==true){
                    
                    $this->modele->supprime_produit($produit_id);
                }
                header("Location: orderitems.php");
                exit();

            }
        }
        // $requete =$this->modele->connexion->query("select * from orderitems,products,orders where products.id=orderitems.product_id and 
        // customer_id = 1 and orders.id = orderitems.order_id and (orders.status = 0 or orders.status = 1)");
        $connexion=isset($_SESSION['connexion']) ? $_SESSION['connexion'] : false;
        $categories = $this->modele->importerTable("categories");
        // $panier = $requete->fetchAll(PDO::FETCH_ASSOC);
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('panier.html.twig', ['panier'=> $panier ,'categories' => $categories, 'total'=> $total,'connexion' => $connexion]);

    }

}

