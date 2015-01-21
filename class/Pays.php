<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pays
 *
 * @author laurent
 */
class Pays extends LinkDB {
    
    protected $id;
    protected $nom;
    
    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }
    
    function save(){
        parent::save();
    }
}
