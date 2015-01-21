<?php


foreach (glob('class/*.php') as $include) {
    include ($include);
}

if(isset($_GET["PayerID"]) && $_GET["paymentId"] && Commande::existCommandeByPaypalRef($_GET["paymentId"])){
    $commande = Commande::findByPaypalReference($_GET['paymentId']);
    if($commande->executePayment($_GET["PayerID"],$_GET['paymentId'])){
        $succes = true;
        $message = "Paiement Accepté, merci !";
        if(isset($_SESSION['panier'])){
            unset($_SESSION['panier']);
	    unset($panier);
        }
    }else{
        $succes = false;
        $message = "Votre paiement a été refusé !";
    }
    
}else{
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="utf-8">
        <meta name="description" content="Site de Ecommerce">
        <meta name="author" content="Huygens Adrien">

        <title>E-commerce</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/shop-homepage.css" rel="stylesheet">


    </head>

    <body>

        <?php include('pagination/Header.php'); ?>

        <div class="container" id="container">

            <div class="row">
                <div class="alert alert-success" role="alert">
                                    <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    <?php if($succes): ?>
                                    <span class="sr-only">Succes:</span>
                                       
                <?php else: ?>
                                    <span class="error">Erreur :</span>
                <?php endif; ?>
                                     <?php echo $message ?>
                                </div>

        <?php
        include ('pagination/footer.php');
        ?>


            </div>
        </div>


    </body>

</html>


    
