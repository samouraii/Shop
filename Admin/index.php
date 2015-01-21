<?php


foreach(glob('../class/*.php') as $include){
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <?php include '../pagination/Header.php'; 
    
     if (isset($_SESSION['username']) && isset($_SESSION['mdp']) && !isset($_GET['deco']) && Personne::verifConnexion($_SESSION['username'], $_SESSION['mdp'])) {
 
     
    ?>
	
	<div class="container" id="container">

        <div class="row">
		
           
            <div class="col-md-12">
                <h1> Administration du site </h1><br />
                
                    <div class='col-sm-2 col-mg-2 col-lg-2 '>
                        <div class="contour-40 h2center">
                            
                            Administration des Users
                    </div>
                   </div>
                <div class='col-sm-2 col-mg-2 col-lg-2 '>
                        <div class="contour-40">
                            <a href="adminProduit.php"alt="" class="lien" > Administration des Produits</a>
                    </div>
                   </div>
                <div class='col-sm-2 col-mg-2 col-lg-2 '>
                        <div class="contour-40 h2center">
                            
                        Administration des Categorie
                    </div>
                   </div>
	</div>
			<!-- Fin du carrousell -->
        </div>
   <?php 
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