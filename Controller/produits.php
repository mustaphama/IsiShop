<?php
require_once __DIR__ . '/../modele/Modele.php';
require_once __DIR__ . '/../vendor/autoload.php';
class Produit{
    public $modele;

    public function __construct() {
        $this->modele = new ModeleWeb4Shop();
    }


    public function import_products () {
        if(isset($_POST['deconnexion'])){
            //s'il se déconnecte on initialise tout
            session_destroy();
            session_start();
            $_SESSION['connexion']=false;
            $_SESSION['panier']=[];
            $_SESSION['total']=0;
        }
        if (isset($_SESSION['panier'])){
            $nb_prod = count($_SESSION['panier']);
        }
        else{
            $nb_prod=0;
        }
        $cat=isset($_GET['cat']) ? $_GET['cat'] : null;
        if ($cat ==null){
            $products = $this->modele->importerTable("products");
        }else{
            $products = $this->modele->import_category($cat);
        }
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        $connexion=isset($_SESSION['connexion']) ? $_SESSION['connexion'] : false;
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('accueil.html.twig', ['products' => $products, 'cat' => $cat,'categories' => $categories,'connexion' => $connexion,'nb_prod'=>$nb_prod]);
    }

    public function getproduct () {
        $id=isset($_GET['prod']) ? $_GET['prod'] : null;
        $product = $this->modele->import_produit($id);
        $avis = $this->modele->import_avis($id);
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        if (isset($_SESSION['panier'])){
            $nb_prod = count($_SESSION['panier']);
        }
        else{
            $nb_prod=0;
        }
        $connexion=isset($_SESSION['connexion']) ? $_SESSION['connexion'] : false;
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('produit.html.twig', ['product' => $product,'avis' => $avis,'categories' => $categories,'connexion' => $connexion,'nb_prod'=>$nb_prod]);
    }

    public function top5(){
        $products = $this->modele->bestprod();
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        if (isset($_SESSION['panier'])){
            $nb_prod = count($_SESSION['panier']);
        }
        else{
            $nb_prod=0;
        }
        $connexion=isset($_SESSION['connexion']) ? $_SESSION['connexion'] : false;
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('top5.html.twig', ['products' => $products,'categories' => $categories,'connexion' => $connexion,'nb_prod'=>$nb_prod]);
    }

}