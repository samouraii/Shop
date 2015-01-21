<?php


//gestion inclusion 
foreach(glob('class/*.php') as $include){
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
		<?php 
               if (isset($_GET['Id_pro']) ){
                $produit = Produit::find($_GET['Id_pro']);
               
                if ($produit->getId()!= NULL)
                {
                   
                ?>
           <h1> Produit</h1>
	 <div class="col-md-8">
			
			<div class="contour">
                                        <div class='col-mg-4 col-lg-4'>
						<img src="<?php echo $produit->getUrl(); ?>" alt="" class="image">
                                        </div>         		
                                                               
                            <div class="col-mg-8 col-lg-8">                    
											<h4 class="aligner padding-4">Nom:</h4><?php echo " ".$produit->getNom(); ?><br/>
                                                                                        <h4 class="aligner padding-4">Categorie:  </h4>
                                                                                        <div class="padding-4"> 
                                                                                        <ul class="padding-4"> 
                                    <?php foreach($produit->getCategories() as $infos){
                                        ?>
                                                                                       <li class="margin-1"><?php echo $infos->getNomCategorie();?></li>                                           
                                        <?php
                                    }
?>                                                  
                                                                                        </ul> </div>
											<h4 class="aligner padding-4">Prix:</h4><?php echo $produit->getPrix(); ?> </br>
											<h4 class="aligner padding-4">Stock:</h4><?php echo $produit->getQuantiteDisponible().' '. $produit->getUnite(); ?></br>
                                                                                        <h4 class="aligner padding-4">Description:</h4></br>
											<div class="padding-5"> 
                                                                                        <p><?php echo $produit->getDescription(); ?></p>
											
                                                                                        </div>
                            <form action="traitement.php" method="POST">
                                <div class="form-group">
                                <label for="quantite" class="col-lg-8 control-label" > Rajouter le produit à votre panier </label> <br/>
                                 <div class="col-lg-8">
                                <input type="text" id="quantite" name="quantite" value="1" size="4"/> 
                                
                                <input type="hidden" name="page"value="<?php echo "webProduit.php?Id_pro=".$produit->getId(); ?>" />
                                <input type="hidden" name="produit"value="<?php echo $produit->getId(); ?>" />
                                <input type='submit'  value="ajouter">
                                 </div>
                                </div>
                                
                                
                            </form>
                            
                            </div>
                        </div>
         </div>
               <?php }
               
               else {
                    
                    echo " <div class='col-md-8'><div class='contour'><h2> Le produit n'existe pas </h2></div></div>";
                    
                }
                }
                
                else {
                    
                    header("Refresh: 0; url=catalogue.php");
                }?>
                      
				<?php include "pagination/MeilleurProduit.php"; ?>
			</div>
		</div>
			<!-- Fin du carrousell -->
   
   <?php 
   include 'pagination/footer.php';;
   ?>

        

        

</body>

</html>
