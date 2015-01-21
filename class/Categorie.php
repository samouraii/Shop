<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categorie
 *
 * @author Adrien Huygens
 */
class Categorie extends LinkDB{

    protected $id;
    protected $nomCategorie;
    private $produit = Array();
    
    function getId() {
        return $this->id;
    }

    function getNomCategorie() {
        return $this->nomCategorie;
    }

    function getProduit() {
        return $this->produit;
    }

    function setNomCategorie($nomCategorie) {
        $this->nomCategorie = $nomCategorie;
    }

    function setProduit($produit) {
        $this->produit = $produit;
    }
    
    static function getAll(){
        $pdo = DatabaseAcces::getInstance();
        $stm = $pdo->prepare("SELECT * FROM categorie");
        $stm->execute();
        $categories = $stm->fetchAll();
        $retour = Array();
        foreach($categories as $categorie){
            $obj = new Categorie();
            $obj->bind($categorie);
            $retour[] = $obj;
        }
        return $retour;
    }
    
    function save(){
        parent::save();
        $this->saveLiaison();
    }
    
    private function saveLiaison(){
        foreach($this->produit as $produit){
            $produit->saveLiaison($this);
        }
    }
    
    function findByNom($nomCategorie){
        return Categorie::generalFind($nomCategorie,"nomCategorie");
    }
    
}
