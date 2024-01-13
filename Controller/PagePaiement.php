<?php
require_once(__DIR__ . '/../Modele/Modele.php');
require_once(__DIR__ . '/../vendor/autoload.php');
require('../Ressources/fpdf_librairy/fpdf.php');
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;
class PagePaiement {
    private $modele;
    public function ObtenirCommandes($orderid) {
        $this->modele = new ModeleWeb4Shop();
        $donnees = $this->modele->recuperer_commande($orderid);
        $this->modele->fermerConnexion();
        return $donnees;
    }
    public function ValiderPaiement($orderid,$methodePaiement) {
        $this->modele = new ModeleWeb4Shop();
        $this->modele->UpdateOrder($orderid,$methodePaiement);
        $this->modele->fermerConnexion();
    }
    public function import_cat(){
        $this->modele = new ModeleWeb4Shop();
        $categorie = $this->modele->importerTable("categories");
        $this->modele->fermerConnexion();
        return $categorie;
    }
}
$controller = new PagePaiement();
$totalEnsemble=0;
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../productimages/Web4ShopHeader.png', 35, 10, 150);
$pdf->Ln(80); 
if (isset($_SESSION['client']['orderid'])){
    $orderid=$_SESSION['client']['orderid'];
    $donnees = $controller->ObtenirCommandes($orderid);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Facture n'.$orderid);
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $headers = array('Nom du produit', 'Prix', 'Quantité', 'Total');
    foreach ($headers as $header) {
        $pdf->Cell(40, 10, utf8_decode($header), 1);
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($donnees as $row) {
        $productName = (strlen($row['name']) > 15) ? substr($row['name'], 0, 15) . '...' : $row['name'];    
        $pdf->Cell(40, 10, utf8_decode($productName), 1); 
        $pdf->Cell(40, 10, $row['price'].' $', 1);
        $pdf->Cell(40, 10, $row['quantity'], 1);
        $total = $row['quantity'] * $row['price'];
        $totalEnsemble+=$total;
        $pdf->Cell(40, 10,  utf8_decode($total.' $'), 1);
        $pdf->Ln();
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Total', 1); 
    $pdf->Cell(40, 10, '', 1); 
    $pdf->Cell(40, 10, '', 1); 
    $pdf->Cell(40, 10, utf8_decode($totalEnsemble.' $'), 1); 
}
//Mets à jour le pdf de la Facture
$pdf->Output('../Ressources/Facture.pdf', 'F');
$clientID = 'AaNHGH6tny4fFZ3pGu60p5dI7m6wKL9GO2hR7X3jTHWu0vUE3s_vL5mFxuOYlti6RsDEX9q_luYetV2Y';
$secret = 'EPuOlLNmFra-twQn6yZLg6YjdHjgQhnRYUNGwKVQXV9D6rwGWcB7VjMJuZjzjS6uWc8oMbGwEW4myTeD';
if (isset($_POST['txn_id'])) {

    $itemName = 'IsiWeb4Shop';
    $itemPrice = $totalEnsemble;
    $currency = 'EUR';
    
    $paypalEndpoint = 'https://api-m.sandbox.paypal.com';
    $clientIdSecret = base64_encode($clientID . ':' . $secret);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paypalEndpoint . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $clientIdSecret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    $tokenResult = curl_exec($ch);
    $accessToken = json_decode($tokenResult)->access_token;
    curl_close($ch);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paypalEndpoint . '/v2/checkout/orders');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ));
    //Mets les paramètres de paiement
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
        'intent' => 'CAPTURE',
        'purchase_units' => array(
            array(
                'amount' => array(
                    'currency_code' => $currency,
                    'value' => $itemPrice
                )
            )
        )
    )));
    $orderResult = curl_exec($ch);
    $orderData = json_decode($orderResult);
    $orderId = $orderData->id;
    curl_close($ch);
    header('Location: https://www.sandbox.paypal.com/checkoutnow?token=' . $orderId);
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $orderId = $_GET['token'];
    
        // Vérifier si le paiement a passé
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalEndpoint . '/v2/checkout/orders/' . $orderId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ));
        $orderResult = curl_exec($ch);
        $orderData = json_decode($orderResult);
        $paymentStatus = $orderData->status;
        curl_close($ch);
    
        if ($paymentStatus === 'COMPLETED') {
            $controller->ValiderPaiement($orderid,'paypal');
    
        } else {
            echo 'Payment not successful. Status: ' . $paymentStatus;
        }
    } else {
        echo 'Payment canceled or failed.';
    }
    

} else {
    $categories = $controller->import_cat();
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('Paiement.html.twig',['prix'=>$totalEnsemble, 'categories'=>$categories]);
}