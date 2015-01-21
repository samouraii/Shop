<?php


foreach (glob('class/*.php') as $include) {
    include ($include);
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
                 <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                                    <span class="sr-only">Error:</span>
                                       Echec du payement
                                </div>

        <?php
        include ('pagination/footer.php');
        ?>


            </div>
        </div>


    </body>

</html>


    