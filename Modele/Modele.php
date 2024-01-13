<?php
if (session_status() == PHP_SESSION_NONE) {
    //démarrer une session s'il y en pas 
    session_start();
    if (!isset($_SESSION['connexion'])){$_SESSION['connexion'] = false; }
    
}
class ModeleWeb4Shop {
    public $connexion;
    public function __construct() {
        try {
            $this->connexion = new PDO("mysql:host=localhost;dbname=web4shop", "root", null);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Échec de la connexion à la base de données : " . $e->getMessage());
        }
    }


    //importer la table passer en parametre
    public function importerTable($table) {
        $sql = "SELECT * FROM $table";
        try {
            $requete = $this->connexion->query($sql);

            $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);

            return $donnees;
        } catch (PDOException $e) {
            die("Échec de la requête SQL : " . $e->getMessage());
        }
    }
    public function fillChart($orderId, $productsid,$quantity) {
        $this->connexion->beginTransaction();
        try {
            $ordersins =$this->connexion->prepare("INSERT INTO orderitems(order_id, quantity, product_id) VALUES(:order_id, :product_id, :quantity);");
            $ordersins->bindParam(':order_id', $orderId);
            $ordersins->bindParam(':product_id', $productsid);
            $ordersins->bindParam(':quantity', $quantity);
            $ordersins->execute();
            $this->connexion->commit();

        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }
    public function addAdresstoOrder($orderId, $delivery_add) {
        $this->connexion->beginTransaction();
        try {
            $ordersins =$this->connexion->prepare("UPDATE orders SET status = 1, delivery_add_id = :delivery_add_id WHERE id = :order_id;");
            $ordersins->bindParam(':order_id', $orderId);
            $ordersins->bindParam(':delivery_add_id', $delivery_add);
            $ordersins->execute();
            $this->connexion->commit();

        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }
    public function UpdateOrder($orderId, $methodePaiement) {
        $this->connexion->beginTransaction();
        try {
            $ordersins =$this->connexion->prepare("UPDATE orders SET status = 2, payment_type = :payment_type, date = :date WHERE id = :order_id;");
            $ordersins->bindParam(':order_id', $orderId);
            $ordersins->bindParam(':payment_type', $methodePaiement);
            $ordersins->bindParam(':date', date('Y-m-d'));
            $ordersins->execute();
            $this->connexion->commit();

        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }
    public function addOrdersUnregistred($customerId, $delivery_add, $status) {
        $this->connexion->beginTransaction();
        try {
            $sessionid = session_id();
            $ordersins =$this->connexion->prepare("INSERT INTO orders (customer_id,registered,delivery_add_id,payment_type, date , status , session,total) 
            VALUES (:customer_id,1,:delivery_add_id,NULL,NULL,:status,:session ,:total)");
            $total = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
            $ordersins->bindParam(':total', $total);
            $ordersins->bindParam(':status', $status);
            $ordersins->bindParam(':customer_id', $customerId);
            $ordersins->bindParam(':delivery_add_id', $delivery_add);
            $ordersins->bindParam(':session', $sessionid);
            $ordersins->execute();
            $Id = $this->connexion->lastInsertId();
            $this->connexion->commit();
            return $Id;

        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }

    public function addDeliveryAddress($firstname, $lastname, $add1, $add2, $add3, $postcode, $phone, $email) {
        $this->connexion->beginTransaction();
        try {
            $customerQuery = "INSERT INTO delivery_addresses (firstname, lastname, add1, add2, city, postcode, phone, email, registered) VALUES (:firstname, :lastname, :add1, :add2, :add3, :postcode, :phone, :email)";
            $customerStmt = $this->connexion->prepare($customerQuery);
            $customerStmt->bindParam(':firstname', $firstname);
            $customerStmt->bindParam(':lastname', $lastname);
            $customerStmt->bindParam(':add1', $add1);
            $customerStmt->bindParam(':add2', $add2);
            $customerStmt->bindParam(':add3', $add3);
            $customerStmt->bindParam(':postcode', $postcode);
            $customerStmt->bindParam(':phone', $phone);
            $customerStmt->bindParam(':email', $email);
            $customerStmt->execute();
            $deliveryId = $this->connexion->lastInsertId();
            $this->connexion->commit();

            return $deliveryId;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }
    public function createUnregistredUser($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email) {
        $this->connexion->beginTransaction();
        try {
            $customerQuery = "INSERT INTO customers (forname, surname, add1, add2, add3, postcode, phone, email, registered) VALUES (:forname, :surname, :add1, :add2, :add3, :postcode, :phone, :email, 1)";
            $customerStmt = $this->connexion->prepare($customerQuery);
            $customerStmt->bindParam(':forname', $forname);
            $customerStmt->bindParam(':surname', $surname);
            $customerStmt->bindParam(':add1', $add1);
            $customerStmt->bindParam(':add2', $add2);
            $customerStmt->bindParam(':add3', $add3);
            $customerStmt->bindParam(':postcode', $postcode);
            $customerStmt->bindParam(':phone', $phone);
            $customerStmt->bindParam(':email', $email);
            $customerStmt->execute();
            $customerId = $this->connexion->lastInsertId();
            $this->connexion->commit();

            return $customerId;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }


    //ajoute une nouvelle ccommande à la table orders
    public function create_commande($customerId,$add,$sessionid){
        $ordersins =$this->connexion->prepare("INSERT INTO orders (customer_id,registered,delivery_add_id,payment_type, date , status , session,total) 
            VALUES (:customer_id,1,:delivery_add_id,NULL,NULL,0,:session ,0)");
            $ordersins->bindParam(':customer_id', $customerId);
            $ordersins->bindParam(':delivery_add_id', $add);
            $ordersins->bindParam(':session', $sessionid);
            $ordersins->execute();
    }
        // cette fonction sert à remplir les tables orders,customers,login,delivery_add au moment de la création d'un compte
    public function createUser($forname, $surname, $add1, $add2, $add3, $postcode, $phone, $email, $username, $password) {
        $this->connexion->beginTransaction();
        try {
            $customerQuery = "INSERT INTO customers (forname, surname, add1, add2, add3, postcode, phone, email, registered) VALUES (:forname, :surname, :add1, :add2, :add3, :postcode, :phone, :email, 1)";
            $customerStmt = $this->connexion->prepare($customerQuery);
            $customerStmt->bindParam(':forname', $forname);
            $customerStmt->bindParam(':surname', $surname);
            $customerStmt->bindParam(':add1', $add1);
            $customerStmt->bindParam(':add2', $add2);
            $customerStmt->bindParam(':add3', $add3);
            $customerStmt->bindParam(':postcode', $postcode);
            $customerStmt->bindParam(':phone', $phone);
            $customerStmt->bindParam(':email', $email);
            $customerStmt->execute();
            $customerId = $this->connexion->lastInsertId();
            $loginQuery = "INSERT INTO logins (id, customer_id, username, password) VALUES (:id, :id, :username, :password)";
            $loginStmt = $this->connexion->prepare($loginQuery);
            $hashedPassword = hash('sha1', $password);//hashage du mots de passe avant son insertion dans la base de données
            $loginStmt->bindParam(':id', $customerId);
            $loginStmt->bindParam(':username', $username);
            $loginStmt->bindParam(':password', $hashedPassword);
            $loginStmt->execute();
            $addinsert =$this->connexion->prepare("INSERT INTO delivery_addresses (firstname, lastname, add1,add2,city,postcode,phone,email) VALUES (:forname,:surname,:add1,:add2,:add3,:postcode,:phone,:email)");
            $addinsert->bindParam(':forname', $forname);
            $addinsert->bindParam(':surname', $surname);
            $addinsert->bindParam(':add1', $add1);
            $addinsert->bindParam(':add2', $add2);
            $addinsert->bindParam(':add3', $add3);
            $addinsert->bindParam(':postcode', $postcode);
            $addinsert->bindParam(':phone', $phone);
            $addinsert->bindParam(':email', $email);
            $addinsert->execute();
            $lastadd = $this->connexion->lastInsertId();
            $sessionid = session_id();
            $this->create_commande($customerId,$lastadd,$sessionid);
            $this->connexion->commit();

            return true;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }


    //ajoute le produit à la table orderitems qui représente le panier
    public function ajout_produit($orderid,$productid,$quantity){
        $this->connexion->beginTransaction();
        try {
            $productadd = $this->connexion->prepare("INSERT INTO orderitems (order_id,product_id,quantity) VALUES (:order_id,:product_id,:quantity)");
            $productadd->bindParam(':order_id', $orderid);
            $productadd->bindParam(':product_id',$productid);
            $productadd->bindParam(':quantity',$quantity);
            $productadd->execute();
            $this->connexion->commit();
            return true;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL  d'ajout : " . $e->getMessage();
            return false;
        }
    }


    // supprime un produit de la table orderitems
    public function supprime_produit($idproduit){
        $this->connexion->beginTransaction();
        try {
            $suppression = $this->connexion->prepare("DELETE FROM orderitems WHERE product_id = :product");
            $suppression->bindParam(':product',$idproduit);
            $suppression->execute();
            $this->connexion->commit();
            return true;
        }catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL  de suppression : " . $e->getMessage();
            return false;
        }
    }


    //mets à jour la quantité d'un produit d'une commande passer en parametre
    public function augmente_quantity($quantity, $idproduit,$orderid){
        try{
            $augmentation = $this->connexion->prepare("UPDATE orderitems SET quantity = :quantity WHERE order_id = :order_id AND product_id = :product_id");
            $augmentation->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $augmentation->bindParam(':order_id', $orderid, PDO::PARAM_INT);
            $augmentation->bindParam(':product_id', $idproduit, PDO::PARAM_INT);
            $augmentation->execute();
            return true;
        }catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL  de modification de quantite : " . $e->getMessage();
            return false;
        }
        
    } 


    //change le status dans la table orders
    public function ChangeStatus($orderid){
        $status = 10;
        try {
            $changement = $this->connexion->prepare("UPDATE orders SET status = :status WHERE id =:order_id");
            $changement->bindParam(':order_id',$orderid,PDO::PARAM_INT);
            $changement->bindParam(':status',$status,PDO::PARAM_INT);
            $changement->execute();
            return true;
        }catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL  de modification de status : " . $e->getMessage();
            return false;
        }
    }


    //donne nous la table des produits les plus vendus
    public function bestprod(){
        $requete = $this->connexion->query("SELECT p.id AS id, p.name AS name,p.image As image , p.description AS description, p.price AS price, SUM(o.quantity) AS total_sold
        FROM products p
        JOIN orderitems o ON p.id = o.product_id
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT 5;
        ");

        $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;

    }


    //cette fonction sert à donner la liste des produits dela categorie passer en parametre
    public function import_category($cat){
        $requete = $this->connexion->query(" select products.id, products.name, products.description, products.image, products.price, products.quantity from products,categories where products.cat_id=categories.id and categories.id=$cat");
        $products = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }

    // importer le produit de l'id passer en parametre
    public function import_produit($id){
        $requete = $this->connexion->query("select * from products where id= $id");
        $product = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $product;
    }

    //importer les avis du produit
    public function import_avis($id){
        $requete1 = $this->connexion->query("select * from reviews where id_product= $id");
        $avis = $requete1->fetchAll(PDO::FETCH_ASSOC);
        return $avis;
    }


    //importer les commandes confirmer ou à confirmer
    public function import_commande(){
        $requete = $this->connexion->query(" select * from orders where status=2 union select * from orders where status=10");
        $orders = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    //importer les informations de la commande d'un client
    public function import_info_commande($username){
        $requete=$this->connexion->prepare("SELECT o.id , o.total FROM orders o join logins l on o.customer_id = l.customer_id  where username = :username and status<10");
        $requete->bindParam(':username',$username);
        $requete->execute();
        $info = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $info;
    }
    public function UsersAdressData($customerUsername){
        $sql = "SELECT forname,surname,phone,email,add1,add2,add3,postcode FROM `customers` c 
        JOIN logins l on c.id=l.customer_id
        WHERE username='$customerUsername';";
        try {
            $requete = $this->connexion->query($sql);

            $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);

            return $donnees;
        } catch (PDOException $e) {
            die("Échec de la requête SQL : " . $e->getMessage());
        }
    }
    //récuperer les produits de la commande d'un client à partir de son id
    public function recuperer_commande($orderid){
        $requete2 = $this->connexion->query("SELECT p.id , p.name , p.price,o.quantity,p.image  FROM orderitems o join products p on o.product_id=p.id  where order_id = $orderid");
        $commandes = $requete2->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }

    //ferme la connexion avec la base de données
    public function fermerConnexion() {
        $this->connexion = null;
    }
}