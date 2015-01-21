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
                <?php if (!isset($_POST['recap'])) { ?>
                    <h1> Le panier </h1>
                    <h2> Vos produits produit </h2>
                    <?php
                } else
                    echo "<h1> récapitulatif de votre commande: </h1>";
                ?>
                <div class="col-md-12">

                    <div class="table-responsive">          
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantiter</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                                    <?php if (!isset($_POST['recap'])) { ?> <th>Supprimer</th> <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($panier->getAll() as $info) {
                                    ?>
                                    <tr>
                                        <td><?php echo $info[0]->getNom(); ?></td>
                                        <td>
                                            <?php if (!isset($_POST['recap'])) { ?>
                                                <form class="aligner" action='traitement.php' method='POST' >
                                                    <button type="submit" class="btn btn-default">
                                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>

                                                    </button>
                                                    <input type="hidden" name="less" value="<?php echo $info[0]->getID(); ?>" />
                                                    <input type="hidden" name="page" value="panier.php" />

                                                </form>
                                            <?php } ?>

                                            <h4 class="aligner padding-4"> <?php echo $info[1]; ?></h4>
                                            <?php if (!isset($_POST['recap'])) { ?>
                                                <form class="aligner" action='traitement.php' method='POST' >
                                                    <button type="submit" class="btn btn-default">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>

                                                    </button>
                                                    <input type="hidden" name="add" value="<?php echo $info[0]->getID(); ?>" />
                                                    <input type="hidden" name="page" value="panier.php" />

                                                </form>
                                            <?php } ?>

                                        </td>
                                        <td> <?php echo $info[0]->getPrix() . "(Reduction:" . $info[0]->getReduction() . ")"; ?></td>
                                        <td> <?php echo $info[1] * $info[0]->getPrix() * (100 - $info[0]->getReduction()) / 100; ?></td>
                                        <?php if (!isset($_POST['recap'])) { ?> <td>
                                                <form action='traitement.php' method='POST' >
                                                    <button type="submit" class="btn btn-default">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>

                                                    </button>
                                                    <input type="hidden" name="sup" value="<?php echo $info[0]->getID(); ?>" />
                                                    <input type="hidden" name="page" value="panier.php" />

                                                </form>

                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                }
                                ?>



                            </tbody>
                        </table>
                        <?php
                        if (isset($_POST['recap']) && $_POST['recap'] == true) {

                            if (isset($_SESSION['username']) && isset($_SESSION['mdp']) && !isset($_GET['deco']) && Personne::verifConnexion($_SESSION['username'], $_SESSION['mdp'])) {
								$personne = Personne::getByUsername($_SESSION['username']);
                                if (isset($_POST['addresseFac']) && isset($_POST['addresseLiv'])) {
                                    
									
									$panier->setAdresseLivraisonId($_POST['addresseFac']);
                                    $panier->setAdresseFacturationId($_POST['addresseLiv']);
                                    $adresseFac = Adresse::find($_POST['addresseFac']);
                                    
                                    $adresseLiv = Adresse::find($_POST['addresseLiv']);
                                }
                                ?>
                                <div class="col-md-10">
                                    <div class="col-md-5">
                                        <div class="thumbnail">
                                            <h4>Adresse de facturation: </h4>
                                            <label class="control-label"> Pour:</label> <?php echo " " . $personne->getCivilite() . " " . $personne->getNom() . " " . $personne->getPrenom(); ?><br />
                                            <label class="control-label">Rue:</label> <?php echo " " . $adresseFac->getRue(); ?>  <br/>  
                                            <label class="control-label"> Ville:</label> <?php echo " " . $adresseFac->getville()->getNom(); ?>  <label class="control-label">code Postal:</label> <?php echo " " . $adresseFac->getville()->getCodePostal(); ?><br/>
                                            <label class="control-label">Pays:</label> <?php echo " " . $adresseFac->getPays()->getNom(); ?>  <br/>  
                                        </div>
                                    </div> 
                                    <div class="col-md-5">
                                        <div class="thumbnail">
                                            <h4>Adresse de livraison: </h4>
                                            <label class="control-label"> Pour:</label> <?php echo " " . $personne->getCivilite() . " " . $personne->getNom() . " " . $personne->getPrenom(); ?><br />
                                            <label class="control-label">Rue:</label> <?php echo " " . $adresseLiv->getRue(); ?>  <br/>  
                                            <label class="control-label"> Ville:</label> <?php echo " " . $adresseLiv->getville()->getNom(); ?>  <label class="control-label">code Postal:</label> <?php echo " " . $adresseLiv->getville()->getCodePostal(); ?><br/>
                                            <label class="control-label">Pays:</label> <?php echo " " . $adresseLiv->getPays()->getNom(); ?>  <br/>  

                                        </div>
                                    </div>
                                    <br />
                                </div>

                                <div class="col-md-12">

                                    <form action='paiement.php' method='POST' >

                                        <button type="submit" class="btn btn-default">

                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Payement par paypal
                                        </button>
                                    </form>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                                    <span class="sr-only">Error:</span>
                                    Vous devez être connecter pour passer commande !!!!
                                </div>

                                <?php
                            }
                            ?>


                            <?php
                        } else {

                            if ($panier->getNbProduit() >0 && isset($_SESSION['username']) && isset($_SESSION['mdp']) && !isset($_GET['deco']) && Personne::verifConnexion($_SESSION['username'], $_SESSION['mdp'])) {
                                ?>
                                <form action='panier.php' method='POST' >
                                    <div class="form-group">
                                        <label for=adresseFac" class="control-label">Adresse de facturation:</label>
        <?php
        $personne = Personne::getByUsername($_SESSION['username']);
		
        $addresse = $personne->getAdresse();
        ?>
                                        <SELECT name="addresseFac" size="1">

                                        <?php
										
                                        foreach ($addresse as $info):
                                            echo '<OPTION value="' . $info->getId() . '">' . $info->getRue() . '</option>';
                                        endforeach;
                                        ?>
                                        </select> 
                                        <br>
                                        <label for=adresseLiv" class="control-label">Adresse de livraison:</label>
                                        <SELECT name="addresseLiv" size="1">

        <?php
        foreach ($addresse as $info):
            echo '<OPTION value="' . $info->getId() . '">' . $info->getRue() . '</option>';
        endforeach;
        ?>
                                        </select> 
                                    </div>
                                    <input type="hidden" name="recap" value="true" />
                                    <button type="submit" class="btn btn-default">

                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        passer commande
                                    </button>
                                </form>
        <?php
    } 
    else if ($panier->getNbProduit() ==0){
        ?>
                        <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                                    <span class="sr-only">Error:</span>
                                   Votre panier est vide !!!!
                                </div>
                        
                        <?php 
    }
    
    else {
        ?>

                                <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                                    <span class="sr-only">Error:</span>
                                    Vous devez être connecter pour passer commande !!!!
                                </div>

        <?php
    }
}
?>

                    </div>


                </div>

            </div>
            <!-- Fin du carrousell -->

<?php
include ('pagination/footer.php');
?>





    </body>

</html>



