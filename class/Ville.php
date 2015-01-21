<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ville
 *
 * @author laurent
 */
class Ville extends LinkDB {

    protected $id;
    protected $nom;
    protected $codePostal;

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function getCodePostal() {
        return $this->codePostal;
    }

    function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;
    }
    
    function save(){
        parent::save();
    }

}
