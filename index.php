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

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <?php include('pagination/Header.php'); ?>

        <div class="container" id="container">

            <div class="row">
                <h1> Accueil !!!</h1>
                <h2> Les derniers produit </h2>
                <div class="col-md-8">

                    <div class="row carousel-holder">

                        <div class="col-md-14">
                            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                                     <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                                </ol>
                                <div class="carousel-inner">
                                   
                                         <?php
                                         $filtre = new FiltreProduit();
                                         $filtre->addDateAjout("DOWN");
                                         $paginator = new Paginator("Produit", 4,$filtre);
                                         $produit = $paginator->GetFirstPage();
                                      
                                         foreach ($produit as $compteur =>$pro):
                                         
                                         ?>
                                       <div class="item <?php if ($compteur==0)echo "active";?>">
                                        <div class="col-sm-6 col-lg-6 col-md-6 col-centered">
                                            <div class="center-block ">

                                                <div class="thumbnail">
                                                    <img src="<?php echo $pro->getUrl(); ?>" alt="">
                                                    <div class="caption">
                                                        <h4 class="pull-right"><?php echo $pro->getPrix()." €"; ?></h4>
                                                        <h4><a href="<?php echo"webProduit.php?Id_pro=" . $pro->getId(); ?>"><?php echo $pro->getNom(); ?></a>
                                                        </h4>
                                                         <p><?php echo $pro->getShortDescription(40); ?> <a  href="<?php echo"webProduit.php?Id_pro=" . $pro->getId(); ?>">Lire la suite</a>.</p>
                                                    </div>
                                                    <div class="ratings">
                                                        <p class="pull-right">15 reviews</p>
                                                        <p>
                                                            <span class="glyphicon glyphicon-star"></span>
                                                            <span class="glyphicon glyphicon-star"></span>
                                                            <span class="glyphicon glyphicon-star"></span>
                                                            <span class="glyphicon glyphicon-star"></span>
                                                            <span class="glyphicon glyphicon-star"></span>
                                                        </p>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                       </div>       
                                        <?php
                                     endforeach;
                                        
                                        ?>
                                   




                                    
                                </div>
                                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                </a>
                                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
                <?php include "pagination/MeilleurProduit.php"; ?>
            </div>
        </div>
        <!-- Fin du carrousell -->

        <?php
        include ('pagination/footer.php');
        ?>





    </body>

</html>


    