<?php
include __DIR__ . '/../modele/Modele.php';
include __DIR__ . '/../vendor/autoload.php';
class Produit{
    public $modele;

    public function __construct() {
        $this->modele = new ModeleWeb4Shop();
    }


    public function import_products () {
        // $mod = new Modele();
        $cat=isset($_GET['cat']) ? $_GET['cat'] : null;
        if ($cat ==null){
            $products = $this->modele->importerTable("products");
        }else{
            $requete = $this->modele->connexion->query(" select products.id, products.name, products.description, products.image, products.price, products.quantity from products,categories where products.cat_id=categories.id and categories.id=$cat");
            $products = $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('accueil.html.twig', ['products' => $products, 'cat' => $cat,'categories' => $categories]);
    }

    public function getproduct () {
        $id=isset($_GET['prod']) ? $_GET['prod'] : null;
        $requete = $this->modele->connexion->query("select * from products where id= $id");
        $product = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete1 = $this->modele->connexion->query("select * from reviews where id_product= $id");
        $avis = $requete1->fetchAll(PDO::FETCH_ASSOC);
        $categories = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('produit.html.twig', ['product' => $product,'avis' => $avis,'categories' => $categories]);
    }

    public function getcategorie() {
        $cat=$_GET['cat'];
        $requete = $this->modele->connexion->query(" select * from products,categories where products.cat_id=categories.id and categories.id=$cat");
        $product = $requete->fetchAll(PDO::FETCH_ASSOC);
        $this->modele->fermerConnexion();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('produit.html.twig', ['product' => $product]);
    }

}