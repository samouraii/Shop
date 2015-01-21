<?php 
    
    session_start(); 
//gestion inclusion 
foreach(glob('class/*.php') as $include){
    include ($include);
}

    if(isset($_SESSION['panier'])){
        $panier = unserialize(stripslashes($_SESSION['panier']));
    }else{
        $panier = new Commande();
    }





?>

<?php

//include("pagination/Header.php");

if(isset($_POST["page"]) && isset($_POST['quantite']) && isset($_POST['produit']) && is_int((int)$_POST['quantite'])){
   
    $id = Produit::find($_POST['produit']);
    $page=$_POST["page"];
    
    if ($id->getId() != NULL){
        
        $panier->addProduit($id, htmlentities($_POST['quantite']));
         $_SESSION['panier'] = serialize($panier);
         header("Refresh: 0; url=$page");   
    }
    else{
     $_SESSION['panier'] = serialize($panier);
     Message::messages("Une Erreur s'est produite !!!!");
    header("Refresh: 0; url=catalogue.php");
}

    }
     else if(isset($_POST["page"]) && isset($_POST['less']) ){
          
         $Pro = Produit::find($_POST['less']);
      if ($Pro->getId() != NULL){
          
       $panier->addProduit ($Pro, $panier->getQuantite($Pro)-1);
     }
      else{
          Message::messages("Le produit n'est plus dans votre panier!!!!");
      }
      $page = $_POST['page'];
      $_SESSION['panier'] = serialize($panier);
      header("Refresh: 0; url=$page");
     }
     
     
     else if(isset($_POST["page"]) && isset($_POST['add']) ){
         
        $Pro = Produit::find($_POST['add']);
      if ($Pro->getId() != NULL){
          
       $panier->addProduit ($Pro, $panier->getQuantite($Pro)+1);
     }
      else{
          Message::messages("Le produit n'est plus dans votre panier!!!!");
      }
      $page = $_POST['page'];
      $_SESSION['panier'] = serialize($panier);
      header("Refresh: 0; url=$page");
     }
     
     
     else if(isset($_POST["page"]) && isset($_POST['add']) ){
         
        $Pro = Produit::find($_POST['add']);
      if ($Pro->getId() != NULL){
          
       $panier->addProduit ($Pro, $panier->getQuantite($Pro)+1);
     }
      else{
          Message::messages("Le produit n'est plus dans votre panier!!!!");
      }
      $page = $_POST['page'];
      $_SESSION['panier'] = serialize($panier);
      header("Refresh: 0; url=$page");
     }
	 elseif(isset($_POST["page"]) && isset($_POST['sup'])){
      $Pro = Produit::find($_POST['sup']);
      if ($Pro->getId() != NULL){
          
        $panier->deleteProduit($Pro);
      
      }
      else{
          Message::messages("Le produit n'est plus dans votre panier!!!!");
      }
      $page = $_POST['page'];
       $_SESSION['panier'] = serialize($panier);
      header("Refresh: 0; url=$page");
  }
     
    
  
  elseif(isset($_GET["activ"]) && isset($_GET['username'])){
      $user = Personne::getNonActiveUserByUsername($_GET['username']);
      $_SESSION['panier'] = serialize($panier);
     
      if ($user->getId() != NULL){
          if ($user->getActivate())
          {
              Message::messages("Votre compte est déjà actif !!!!");
                header("Refresh: 0; url=index.php");
          }
          else{
              if($user->getToken() === $_GET["activ"] ){
                  $user->setActivate(True);
                  $user->save();
                  Message::messages_S("Votre compte est activé !!!!");
                    header("Refresh: 0; url=index.php");
                  
                  echo "yes";
              }
               else{
                   Message::messages("Une Erreur c'est produite !!!!");
                header("Refresh: 0; url=index.php");
               }
          }
          
         
      }
      else {
         Message::messages("Le compte n'existe pas !!!!");
         header("Refresh: 0; url=index.php");
      }
     
  }
   
  
  
    
else{
        
        Message::messages("Veuillez renseigner une quantiter!!!!");
         $_SESSION['panier'] = serialize($panier);
        header("Refresh: 0; url=catalogue.php");
    }
  
   




?>