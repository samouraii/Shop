<?php
ini_set('display_errors','1');
error_reporting(E_ALL);

session_start();

foreach (glob("class/*.php") as $include){
    include($include);
}

if(isset($_SESSION["panier"]) && isset($_SESSION['username']) && isset($_SESSION['mdp']) && Personne::verifConnexion($_SESSION['username'], $_SESSION["mdp"])){
    $commande = unserialize($_SESSION["panier"]);
    $commande->passerCommande(Personne::getByUsername($_SESSION['username']));
}else{
    echo "merde";
    throw new Exception("Vous n'avez pas le droit de voir cette page");
    
}

 
