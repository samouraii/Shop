<?php


//gestion des inclussions
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
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="css/shop-homepage.css" rel="stylesheet">
    </head>

    <body>

        <?php include 'pagination/Header.php'; ?>

        <div class="container" id="container">

            <div class="row">

                <h2> Nos produits </h2>

                <!-- mettre le code pour le filtre ici -->

                <div class="col-md-7 col-lg-7">
                    <!-- zone de filtre -->
                    <div class="row">
                        filtre
                        <?php
                        if (isset($_SESSION['filtre']) && $_POST == NULL) {
                            $filtre = unserialize($_SESSION['filtre']);
                            unset($_SESSION['filtre']);
                        } else {

                            $filtre = new FiltreProduit();
                            if (isset($_GET['p']) && $_GET['p'] == 'ok') {
                                $list = array("categorie", "nomT", "prix", "dateT");
                                $tab = array();
                                $tab2 = array();
                                foreach ($list as $info) {

                                    if (isset($_POST[$info])) {
                                        
                                        $tab[] = $_POST[$info];
                                    } else if (isset($_GET[$info])) {
                                        $tab[] = $_GET[$info];
                                    } else {
                                        $tab[] = -1;
                                    }
                                }




                                if ($tab[0] != -1) {
                                    foreach ($_POST['categorie'] as $cat) {

                                        $tab2[] = Categorie::find($cat);
                                    }
                                    $filtre->addCategories($tab2);
                                }
                                if ($tab[1] != -1) {
                                    if ($tab[1] == "UP" | $tab[1] == "DOWN") {
                                        $filtre->addTriNom($tab[1]);
                                    }
                                }

                                if ($tab[2] != -1) {
                                    $tmp = explode('-', $tab[2]);
                                    $tabFiltre = array();
                                    if (count($tmp) == 2) {
                                        if ((int) $tmp[0] <= (int) $tmp[1]) {
                                            $tabFiltre[] = (int) $tmp[0];
                                            $tabFiltre[] = (int) $tmp[1];
                                        } else {
                                            $tabFiltre[] = (int) $tmp[1];
                                            $tabFiltre[] = (int) $tmp[0];
                                        }
                                    } else if (count($tmp) == 1) {
                                        $tabFiltre[] = "MIN";
                                        $tabFiltre[] = (int) $tmp;
                                    } else {
                                        $tabFiltre[] = "MIN";
                                        $tabFiltre[] = "MAX";
                                    }
                                    $filtre->addPrixBetween($tabFiltre[0], $tabFiltre[1]);
                                }
                                if ($tab[3] != -1) {
                                    if ($tab[3] == "UP" | $tab[3] == "DOWN") {
                                        $filtre->addDateAjout($tab[3]);
                                    }
                                }
                            }
                        }
                        ?>
                        <form role="form" method="POST"class="form-horizontal"action ="catalogue.php?p=ok">
                            <div class="form-group">
                                <label for="cat" class="col-lg-3  control-label">Categorie</label>
                                <div class="col-lg-3">
                                    <div class="cat" id="cat">
                                        <?php
                                        foreach (Categorie::getAll() as $pro) {
                                            ?>
                                            <input type="checkbox" d="cat" name="categorie[]" value="<?php echo $pro->getId(); ?>"><?php echo $pro->getNomCategorie(); ?><br/>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                                <label for="cat" class="col-lg-3 control-label">prix : </label>

                                <div class="col-lg-3">
                                    <input type="text" name="prix" placeholder="entrez votre prix: 0-200">

                                </div>


                            </div>
                            <div class="form-group">
                                <label for="m" class="col-lg-3 control-label">trier le nom:</label>

                                <div class="col-lg-3">
                                    <input name="nomT" id="m" type="radio" value="UP">Croissant </input><br>
                                    <input name="nomT" id="m" type="radio" value="DOWN">Decroissant </input>
                                </div>
                                <label for="m2" class="col-lg-3 control-label">trier le date d'ajout:</label>
                                <div class="col-lg-3">
                                    <input name="dateT" id="m2" type="radio" value="UP">Croissant </input><br>
                                    <input name="dateT" id="m2" type="radio" value="DOWN">Decroissant </input>
                                </div>

                                <input type="submit" class="col-lg-12"value="enregistrer"/>
                        </form>            

                    </div>

                    <hr>

                    <div class="row">
                        <?php
//creation de nos X produit avec l'outil paginator qui permet de gerer la paginaions

                        $_SESSION['filtre'] = serialize($filtre);

                        $paginator = new Paginator("Produit", 12, $filtre);

                        $nb = $paginator->getNbPage();
                        if (isset($_GET["page"]) && is_int((int) $_GET["page"]) && $_GET["page"] > 1 && $_GET["page"] <= $nb) {
                            $produit = $paginator->getPage($_GET["page"]);
                        } else {
                            $produit = $paginator->GetFirstPage();
                        }

                        foreach ($produit as $pro) :
                            ?>
                            <div class="col-sm-4 col-lg-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="<?php echo $pro->getUrl(); ?>" alt="">
                                    <div class="caption">
                                        <h4 class="pull-right"><?php echo $pro->getPrix() . " €"; ?></h4>
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

                        <?php endforeach; ?>


                    </div>
                    Page <?php
                    for ($i = 1; $i <= $nb; $i++) {
                        if ($i > 1) {
                            echo " | ";
                        } echo "<a href='?page=$i'>$i</a>";
                    }
                    ?>
                </div>

            </div>
            <?php include "pagination/MeilleurProduit.php"; ?>
        </div>
    </div>
    <!-- Fin du carrousell -->
    <?php include 'pagination/footer.php'; ?>





</body>

</html>

