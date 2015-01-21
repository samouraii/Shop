<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Produit
 *
 * @author  Adrien Huygens
 */
class Produit extends LinkDB implements Paginable {

    protected $id;
    protected $nom;
    protected $description;
    protected $prix;
    protected $tauxTVA;
    protected $quantiteDisponible;
    protected $unite;
    protected $reduction;
    protected $dateAjout;
    protected $url;
    protected $active;
    
    private $categories = Array();

    public function __construct($tab = null) {
        if (isset($tab)) {
            $this->nom = $tab[0];
            $this->description = $tab[1];
            $this->prix = $tab[2];
            $this->tauxTVA = $tab[3];
            $this->quantiteDisponible = $tab[4];
            $this->unite = $tab[5];
            $this->reduction = $tab[6];
            $this->url = $tab[7];
            $this->active = $tab[8];
        }
    }

    function getCategories() {
        return $this->categories;
    }

    function setCategorie(Categorie $categorie) {
        $this->categories[] = $categorie;
    }

    function setCategories(Array $categories) {
        $this->categories = array_merge($categories, $this->categories);
    }

    function getUnite() {
        return $this->unite;
    }

    function setUnite($unite) {
        $this->unite = $unite;
    }

    function getActive() {
        return $this->active;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getDescription() {
        return $this->description;
    }

    function getPrix() {
        return $this->prix;
    }

    function getTauxTVA() {
        return $this->tauxTVA;
    }

    function getQuantiteDisponible() {
        return $this->quantiteDisponible;
    }

    function getReduction() {
        return $this->reduction;
    }

    function getDateAjout() {
        return $this->dateAjout;
    }

    function getUrl() {
        return $this->url;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPrix($prix) {
        $this->prix = $prix;
    }

    function setTauxTVA($tauxTVA) {
        $this->tauxTVA = $tauxTVA;
    }

    function setQuantiteDisponible($quantiteDisponible) {
        $this->quantiteDisponible = $quantiteDisponible;
    }

    function setReduction($reduction) {
        $this->reduction = $reduction;
    }
    function setUrl($url) {
        $this->url = $url;
    }

    function save() {
        if(!$this->id){
            $this->dateAjout = date("Y-m-d H:i:s");
            $this->active = true;
        }
        parent::save();
        $this->saveLiaison();
    }

    protected function bind($tab) {
        parent::bind($tab);
        //On cherches les éventuels objets liés
        $pdo = DatabaseAcces::getInstance();
        $stm = $pdo->prepare("SELECT categorieproduit.categorieId AS id FROM categorieproduit WHERE categorieproduit.produitId = :id");
        $stm->execute(array(':id' => $this->id));
        while ($donne = $stm->fetch()) {
            if ($obj = Categorie::find($donne["id"])) {
                $this->setCategorie($obj);
            }
        }
    }

    private function saveLiaison() {

        foreach ($this->categories as $categorie) {
            if ($categorie->getId() == null) {
                $categorie->save();
            }

            $pdo = DatabaseAcces::getInstance();
            $stm = $pdo->prepare("INSERT INTO categorieproduit VALUES ('',:categorieId,:produitId)");
            $stm->execute(array(
                ':categorieId' => $categorie->getId(),
                ':produitId' => $this->id
            ));
        }
    }

    function getShortDescription($nbCaracteres) {
        return mb_strimwidth($this->description, 0, $nbCaracteres);
    }

    static function getByPagination($offset, $limit, $filtre = null) {
         $pdo = DatabaseAcces::getInstance();
        if($filtre && $filtre instanceof FiltreProduit){
            $retour = $filtre->getSql();
            $sql = $retour[0]." LIMIT :offset, :limit";
            $param = $retour[1];
        }else{
            $sql = "SELECT * FROM produit WHERE active = :active ORDER BY dateAjout ASC LIMIT :offset, :limit";
        }
        $req = $pdo->prepare($sql);
        $req->bindValue('offset',$offset,PDO::PARAM_INT);
        $req->bindValue('limit',$limit,PDO::PARAM_INT);
        if(isset($param)){
            foreach ($param as $key => $para){
                $req->bindValue($key,$para);
            }
        }else{
            $req->bindValue('active',TRUE,PDO::PARAM_BOOL);
        }
        $req->execute();
        $retour = Array();
        $produits = $req->fetchAll();
        foreach($produits as $produit){
            $obj = new Produit();
            $obj->bind($produit);
            $retour[] = $obj;
        }
        return $retour;
    }
    
    public static function countAll($filtre=NULL) {
        if($filtre){
            $tab = $filtre->getSql();
            
            $sql = str_replace("*", "COUNT(produit.id) AS nb",$tab[0] );
            
            $param = $tab[1];
        }else{
            $sql = "SELECT COUNT(id) AS nb FROM produit WHERE active = 1";
            $param = NULL;
        }
        $req = self::execute($sql,$param);
        $retour = $req->fetchAll();
        return $retour[0]["nb"];
    }
    
    function isCategorie(Categorie $cat){
        return in_array($cat, $this->categories);
    }

}
