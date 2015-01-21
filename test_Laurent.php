<?php

//Inclusion des fichiers
foreach(glob('class/*.php') as $include){
    include($include);
}

//$produit = new Produit();
//
//$produit->setDescription("Très beau pull");
//$produit->setNom("Le costume du Che");
//$produit->setPrix("20");
//$produit->setQuantiteDisponible("30");
//$produit->setReduction(0);
//$produit->setUnite("m");
//$produit->setUrl("Coucou");
//$produit->setTauxTVA(7);
//$produit->setActive(true);
//
//$categorie = new Categorie();
//$categorie->setNomCategorie("Vêtements classes");
//
//$produit->setCategorie($categorie);
//
//$produit->save();


//$panier = new Commande();
//
//$produit = Produit::find("pro54b06932c81074.85942420");
//
//$panier->addProduit($produit, "3");
//$panier->setReduction(0);
//
//$panier->setAdresseFacturationId("adr54b049222f1821.55759931");
//$panier->setAdresseLivraisonId("adr54b049222f1821.55759931");

//$panier->passerCommande(Personne::find("per54b054b0183fc4.53547598"));
//
//foreach($panier->getAll() as $duo){
//    echo $duo[1]." x ".$duo[0]->getPrix()." - ".$duo[0]->getNom()."<br />";
//}
//
//echo $panier->getNbProduit();

mail("laurent.cardon@jsb.be","Tu as de beaux yeux","J'aime tes yeux ... choux");