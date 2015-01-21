
<?php


foreach (glob('../class/*.php') as $include) {
    include ($include);
}

     
     
?>
<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="utf-8">
        <meta name="description" content="Site de Ecommerce">
        <meta name="author" content="Huygens Adrien">

        <title> Administration </title>
        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../css/shop-homepage.css" rel="stylesheet">

        

    </head>

    <body>

<?php include '../pagination/Header.php'; 


 if (isset($_SESSION['username']) && isset($_SESSION['mdp']) && !isset($_GET['deco']) && Personne::verifConnexion($_SESSION['username'], $_SESSION['mdp'])) {
 

?>

        <h1> Administration: </h1>
<?php
if (isset($_GET['Id_pro'])) {
    $produit = Produit::find($_GET['Id_pro']);
    if (isset($_GET['Id_save'])) {

        $produit->setNom($_POST['nom']);
        $produit->setDescription($_POST['description']);
        $produit->setPrix($_POST['prix']);
        $produit->setTauxTVA($_POST['tauxTva']);
        $produit->setQuantiteDisponible($_POST['quantiteDisponible']);
        $produit->setUnite($_POST['unite']);
        $produit->setUrl($_POST['url']);
        $produit->setReduction($_POST['reduction']);
        $produit->setActive(True);

        $tab = Array();
        if (isset($_POST["categorie"]) && $_POST["categorie"] != ""){
        foreach ($_POST["categorie"] as $value) {
            $tab[] = Categorie::find($value);
        }
        }
        if (isset($_POST["categorie2"]) && $_POST["categorie2"] != NULL) {
            foreach ((explode(';', $_POST["categorie2"])) as $value) {
                $cat = new Categorie();
                $cat->setNomCategorie($value);
                $tab[] = $cat;
            }
        }
        $produit->setCategories($tab);
        $produit->save();
    } else {
        ?>
                <div class="container" id="container">

                    <div class="row">
                        <div class="col-lg-8">
                            <form role="form" class="form-horizontal"method="POST" action="adminProduit.php?Id_pro=<?php echo $produit->getId() . "&Id_save=1"; ?>">
                                <div class="form-group">
                                    <label for="nom" class="col-lg-4 control-label">Nom</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="nom" name="nom"type="text" value="<?php echo $produit->getNom(); ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-lg-4 control-label">Description</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="description" name="description" type="text" value="<?php echo $produit->getDescription(); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="prix" class="col-lg-4 control-label">Prix</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="prix" name="prix" type="text" value="<?php echo $produit->getPrix(); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tauxTva" class="col-lg-4 control-label">Taux de TVA</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="tauxTva" name="tauxTva" type="text" value="<?php echo $produit->getTauxTVA(); ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="quantiteDisponible" class="col-lg-4 control-label">Quantité dispognible</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="quantiteDisponible"name="quantiteDisponible" type="text" value="<?php echo $produit->getQuantiteDisponible(); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="unit" class="col-lg-4 control-label">Unité de stock</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="unite" name="unite" type="text" value="<?php echo $produit->getQuantiteDisponible(); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">        
                                    <label for="reduction" class="col-lg-4 control-label">Reduction</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="reduction"name="reduction" type="text" value="<?php echo $produit->getReduction(); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-lg-4 control-label">Image</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="url"name="url" type="text" value="<?php echo $produit->getUrl(); ?>"/>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="categorie" class="col-lg-4 control-label">Categorie (multiple)</label>
                                    <div class="col-lg-8">
                                        <select  class="form-control"  name="categorie[]"  multiple>
        <?php
        foreach (Categorie::getAll() as $categorie) {
            $selection = "";
            if ($produit->isCategorie($categorie)) {
                $selection = 'selected';
            }
            echo '<option  value="' . $categorie->getId() . '" ' . $selection . '>' . $categorie->getNomCategorie() . '</option>';
        }
        ?>

                                        </select>
                                        <input class="form-control" id="categorie2" name="categorie2" type="text"placeholder="; entre 2 nouvelle categories" />                           </div>


                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-lg-4 control-label"></label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="url" type="submit" value="Enregistrer"/>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

        <?php
        // enregistrement de mon object ou affichage du formulaire 
    }
} else {

    //affichage de tous les objects 
    echo "  <div  class='col-xs-4 col-sm-12 col-lg-12 col-md-12'><h2> gestion de produit</h2></div>";
    //creation de nos X produit avec l'outil paginator qui permet de gerer la paginaions

    $paginator = new Paginator("Produit", 20);
    
     $nb = $paginator->getNbPage();
     if (isset($_GET["page"]) && is_int((int)$_GET["page"]) && $_GET["page"] > 1 && $_GET["page"] <= $nb) {
       $produit = $paginator->getPage($_GET["page"]);
      } else {
      $produit = $paginator->GetFirstPage();
                        }
    ?>
                <div class="container" id="container">

                    <div class="row">
                        <div class="col-lg-8">
    <?php
    foreach ($produit as $pro) {
        ?>
                                <div  class="col-xs-4 col-sm-12 col-lg-12 col-md-12">
                                    <div class="thumbnail">
                                        <a href="?Id_pro=<?php echo$pro->getId(); ?> "alt="" class="lien" ><?php echo $pro->getNom() . " -- " . $pro->getId(); ?> </a>                        

                                    </div>	  									
                                </div>                      



        <?php
    }
    ?>

                            <div  class="col-xs-4 col-sm-12 col-lg-12 col-md-12">
                                <div class="thumbnail">
                                    <a href="?Id_pro= "alt="" class="lien" >Ajout d'un produit </a>                        

                                </div>	  									
                            </div>   


                        </div>
                    </div>
                     Page <?php for($i=1;$i<=$nb;$i++){if($i>1){echo " | ";} echo "<a href='?page=$i'>$i</a>";} ?>
                </div>



    <?php
}

include '../pagination/footer.php';
?>

    </body>

</html>

<?php
   
 }
 
 else{
      header("Refresh: 0; url=../");
     
 }

?>