<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paginator
 *
 * @author laurent
 */
class Paginator {

    private $nomClasse;
    private $nbPage;
    private $limit;
    private $filtre;

    public function __construct($nomClasse, $limit, $filtre = null) {
        if ($tab = class_implements($nomClasse)){
            if (array_search("Paginable", $tab)) {
                $this->nomClasse = $nomClasse;
                $this->limit = $limit;
                $this->nbPage = ceil((int)$nomClasse::countAll($filtre) / (int) $limit);
                $this->filtre = $filtre;
            } else {
                throw new Exception('Cette classe n\'implémente pas l\'interface "paginable".');
        
            } 
    
            
            }else {
            throw new Exception('Cette classe n\'implémente pas l\'interface "paginable".');
        }
    }   

    function GetFirstPage() {
        $classe = $this->nomClasse;
        return $classe::getByPagination(0,$this->limit,$this->filtre);
    }
    
    function getPage($numPage){
        if($numPage>$this->nbPage || $numPage<1){
            return false;
        }else{
            $classe = $this->nomClasse;
            return $classe::getByPagination(($numPage-1)*$this->limit,$this->limit,$this->filtre);
        }
    }
    
    function getNbPage(){
        return $this->nbPage;
    }
}
