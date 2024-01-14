<?php
require_once __DIR__ . '/../modele/Modele.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Panier{
    public $modele;


    public function __construct(){
        $this->modele = new ModeleWeb4Shop();
    }

    public function getorders(){
        //on récupere ce qu'il y a dans le panier et le totale s''il y en a
        $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
        $total = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            
            if(isset($_POST['ajouterAuPanier'])){
                if (isset($_POST['prod']) &&  isset($_POST['quantite'])){
                    //récupération de l'id du produit et de la quantité selectionné
                    $produit = $_POST['prod'];
                    $quantite = $_POST['quantite'];
                    $prod = $this->modele->import_produit($produit);
                    if (array_key_exists($produit,$panier)){
                        //si le produit existe dans le panier on fait une somme des quantités de ce produit
                        $panier[$produit]['quantity']+= $quantite;
                        if ($_SESSION['connexion']==true){
                            $this->modele->augmente_quantity($panier[$produit]['quantity'],$produit,$_SESSION['client']['orderid']);
                        }
                    }
                    else{//sinon à l'ajoute au panier
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
                //si on appuie surle bouton de suppression on récupere le produit et on le supprime
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
                //redirection vers la page du panier
                header("Location: orderitems.php");
                exit();

            }
        }
        $connexion=isset($_SESSION['connexion']) ? $_SESSION['connexion'] : false;
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('panier.html.twig', ['panier'=> $panier ,'categories' => $categories, 'total'=> $total,'connexion' => $connexion]);

    }

}

