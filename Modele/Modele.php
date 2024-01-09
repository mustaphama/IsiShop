<?php
if (session_status() == PHP_SESSION_NONE) {
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
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
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
            $ordersins =$this->connexion->prepare("INSERT INTO orders (customer_id,registered,delivery_add_id,payment_type, date , status , session,total) 
            VALUES (:customer_id,1,:delivery_add_id,NULL,NULL,0,:session ,0)");
            $ordersins->bindParam(':customer_id', $customerId);
            $ordersins->bindParam(':delivery_add_id', $lastadd);
            $ordersins->bindParam(':session', $sessionid);
            $ordersins->execute();
            $this->connexion->commit();

            return true;
        } catch (PDOException $e) {
            $this->connexion->rollBack();
            echo "Échec de la requête SQL : " . $e->getMessage();
            return false;
        }
    }

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

    public function fermerConnexion() {
        $this->connexion = null;
    }
}