<?php

//

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personne
 *
 * @author laurent
 */
class Personne extends LinkDB {

    protected $id;
    protected $nom;
    protected $prenom;
    protected $mdp;
    protected $salt;
    protected $token;
    protected $mail;
    protected $civilite;
    protected $dateDeNaissance;
    protected $active;
    protected $username;
    private $adresse = Array();
    
    
    function getAdresse() {
        return $this->adresse;
    }

    function setAdresse(Adresse $adresse) {
        $this->adresse[] = $adresse;
    }
    
    function setAddresses(Array $adresses){
        $this->adresse = array_merge($adresses,$this->adresse);
    }

    
    function getUsername() {
        return $this->username;
    }

    function setUsername($username) {
        $test = $this->getByUsername($username);
        if(strlen($username)>=5 && $test->getId() === NULL )
        {
        $this->username = $username;
        
        }else{
            throw new Exception("Votre Pseudo dois etre plus comporter au moins 5 caractere ou le pseudo existe déjà");
        }
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getMail() {
        return $this->mail;
    }

    private function getSalt() {
        return $this->salt;
    }

    function getToken() {
        return $this->token;
    }

    function getMdp() {
        return $this->mdp;
    }

    function getCivilite() {
        return $this->civilite;
    }

    function getDateDeNaissance() {
        return $this->dateDeNaissance;
    }

    function getActivate() {
        return $this->active;
    }

    function setNom($nom) {
        if (strlen($nom)> 3 && strlen($nom) <=50){
        $this->nom = $nom;
         }else{
            throw new Exception("Votre Nom dois etre plus comporter au moins 3 caractere");
        }
    }

    function setPrenom($prenom) {
        if (strlen($prenom)> 3 && strlen($prenom) <=50){
        $this->prenom = $prenom;
         }else{
            throw new Exception("Votre prenom dois etre plus comporter au moins 3 caractere");
        }
    }

    function setMail($mail) {
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
       
        $this->mail = $mail;
        }else{
            throw new Exception("Votre Mail doit etre valide");
        }
    }

    function setToken($token) {
        $this->token = $token;
    }

    function setMdp($mdp1,$mdp2) {
        $limit = 5;
       
       
        if($mdp1=== $mdp2 && strlen($mdp1) > $limit)
       {
            if (!$this->getSalt()) {
                $this->salt = '$6$' . sha1($this->dateDeNaissance . $this->username . uniqid() . $this->prenom . rand(0, 999));
            }
            $this->mdp = crypt($mdp1, $this->salt);
        }else{
            throw new Exception("Les mots de passent ne sont pas les même ou il est inférieur à $limit caractères");
        }
    }

    function setCivilite($civilite) {
        $this->civilite = $civilite;
    }

    function setDateDeNaissance($dateDeNaissance) {
        $this->dateDeNaissance = $dateDeNaissance;
    }

    function setActivate($activate) {
        $this->active = $activate;
    }

    static function verifConnexion($username, $mdp) {
        $retour = Personne::executeAndFetchAll("SELECT mdp,salt FROM personne WHERE username = :username AND active = 1", array(":username" => $username));
        if (isset($retour[0]["mdp"]) && isset($retour[0]["salt"]) && $retour[0]["mdp"] === crypt($mdp, $retour[0]["salt"])) {
            return true;
        } else {
            return false;
        }
    }

    function save() {
        if (!$this->id) {
            $this->token = crypt($this->salt . $this->username);
            $this->active = false;
        }
        parent::save();
        if (isset($this->adresse)) {
            $this->saveAdresse();
        }
    }

    private function saveAdresse() {
        $sql = "INSERT INTO adressepersonne VALUES (null,:adresseId,:personneId)";

        foreach ($this->adresse as $adresse) {
            $adresse->save();
            $params = array(
                'adresseId' => $adresse->getId(),
                'personneId' => $this->id
            );
            Personne::execute($sql, $params);
        }
    }
    
    function bind($tab){
        parent::bind($tab);
        $sql = "SELECT adressepersonne.adresseId AS id FROM adressepersonne WHERE adressepersonne.personneId = :id";
        foreach (Personne::executeAndFetchAll($sql,array('id'=>$this->id)) as $adresse){
            $this->adresse[] = Adresse::find($adresse['id']);
        }
    }
    
        static function getByUsername($username){
            return Personne::generalFind($username,'username');
        }
        
        static function getNonActiveUserByUsername($username){
            return Personne::generalFind($username,'username',true,false);
        }
        

}
