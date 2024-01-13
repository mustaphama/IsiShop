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
}
print_r($_SESSION['panier']);
exit;
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

$pdf->Output('../Ressources/Facture.pdf', 'F');
$clientId = 'AaNHGH6tny4fFZ3pGu60p5dI7m6wKL9GO2hR7X3jTHWu0vUE3s_vL5mFxuOYlti6RsDEX9q_luYetV2Y';
$clientSecret = 'EPuOlLNmFra-twQn6yZLg6YjdHjgQhnRYUNGwKVQXV9D6rwGWcB7VjMJuZjzjS6uWc8oMbGwEW4myTeD';
$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);
$apiContext->setConfig([
    'mode' => 'sandbox',
]);

if (isset($_POST['txn_id'])) {
    try {
        // Perform IPN verification
        $post_data = $_POST;
        $post_data['cmd'] = '_notify-validate';

        $ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response == 'VERIFIED') {
            if ($_POST['payment_status'] == 'Completed') {
                $controller->ValiderPaiement($orderid,'paypal');
            } else {
                echo 'Payment status is not Completed';
            }
        } else {
            echo 'IPN Validation Failed';
        }
    } catch (Exception $ex) {
        echo 'Error: ' . $ex->getMessage();
    }
} else {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('Paiement.html.twig',['prix'=>$totalEnsemble]);
}