<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Adresse
 *
 * @author laurent
 */
class Adresse extends LinkDB {

    protected $id;
    protected $rue;
    protected $villeId;
    protected $paysId;
    private $ville;
    private $pays;

    /**
     * 
     * @return Ville
     */
    function getVille() {
        return $this->ville;
    }

    /**
     * 
     * @return Pays
     */
    function getPays() {
        return $this->pays;
    }

    function setVille(Ville $ville) {
        $this->ville = $ville;
    }

    function setPays(Pays $pays) {
        $this->pays = $pays;
    }

    function getId() {
        return $this->id;
    }

    function getRue() {
        return $this->rue;
    }

    function setRue($rue) {
        $this->rue = $rue;
    }

    function save() {

        if (isset($this->ville) && isset($this->pays) && $this->ville instanceof Ville && $this->pays instanceof Pays) {
            $this->ville->save();
            $this->villeId = $this->ville->getId();

            $this->pays->save();
            $this->paysId = $this->pays->getId();

            parent::save();
        }
    }
    
    function bind($tab){
        parent::bind($tab);
        $this->pays = Pays::find($this->paysId);
        $this->ville = Ville::find($this->villeId);
    }

}
