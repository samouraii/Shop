<?php

use \PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;

define("PP_CONFIG_PATH", __DIR__ . '/../vendor/paypal/sdk-core-php/config');

require_once __DIR__ . '/../vendor/autoload.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of commande
 *
 * @author laurent
 */
class Commande extends LinkDB {

    protected $id;
    protected $personneId;
    protected $reduction;
    protected $adresseLivraisonId;
    protected $adresseFacturationId;
    protected $paye;
    protected $livre;
    protected $referencePaypal;
    private $produits = Array();

    function addProduit(Produit $produit, $quantite) {
        if ($quantite > 0 && $produit->getActive()) {
            if ($this->getIndex($produit) !== false) {
                $this->produits[$this->getIndex($produit)] = array($produit, $quantite);
            } else {
                $this->produits[] = array($produit, $quantite);
            }
        } elseif ($quantite === 0) {

            $this->deleteProduit($produit);
        }
    }

    function deleteProduit(Produit $produit) {
        if ($this->getIndex($produit) !== false) {

            unset($this->produits[$this->getIndex($produit)]);
        }
    }

    function getId() {
        return $this->id;
    }

    function getPersoneId() {
        return $this->personeId;
    }

    function getReduction() {
        return $this->reduction;
    }

    function getAdresseLivraisonId() {
        return $this->adresseLivraisonId;
    }

    function getAdresseFacturationId() {
        return $this->adresseFacturationId;
    }

    function getPaye() {
        return $this->paye;
    }

    function getLivre() {
        return $this->livre;
    }

    function getReferencePaypal() {
        return $this->referencePaypal;
    }

    function setPersoneId($personeId) {
        $this->personeId = $personeId;
    }

    function setReduction($reduction) {
        $this->reduction = $reduction;
    }

    function setAdresseLivraisonId($adresseLivraisonId) {
        $this->adresseLivraisonId = $adresseLivraisonId;
    }

    function setAdresseFacturationId($adresseFacturationId) {
        $this->adresseFacturationId = $adresseFacturationId;
    }

    function setPaye($paye) {
        $this->paye = $paye;
    }

    function setLivre($livre) {
        $this->livre = $livre;
    }

    function setReferencePaypal($referencePaypal) {
        $this->referencePaypal = $referencePaypal;
    }
    
    /**
     * 
     * @return /ApiContext
     */
    private function getOAuthPaypal(){
        $oauthCredential = new OAuthTokenCredential("AcqpcxAg0xccMcorgUh2fqgNHeY50RdSxuHYKA0qd6Ym9VYU5heGNsbuDYF5", "EDpW1xAaC0QQ_myzRCwTB4V_twjf68K9IgJfy4oK2_m-chUHRR9V927RbDXl");

        $apiContext = new ApiContext($oauthCredential, 'Request' . time());
        $apiContext->setConfig(array('mode' => 'sandbox'));
        
        return $apiContext;
    }

    function passerCommande(Personne $personne) {
        $this->personneId = $personne->getId();
        $this->paye = false;
        $this->livre = false;
        $this->referencePaypal = "";
        if (!$this->reduction) {
            $this->reduction = "0";
        }

        $apiContext = $this->getOAuthPaypal();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $alItem = new ItemList();
        $total = 0;

        foreach ($this->produits as $duo) {

            $produit = $duo[0];
            $quantite = $duo[1];

            $prix = round($produit->getPrix() * (100 - $produit->getReduction()) / 100, 2);
            $total += $quantite * $prix;

            $item = new Item();
            $item->setQuantity($quantite);
            $item->setName($produit->getNom());
            $item->setPrice(money_format('%!i', $prix));
            $item->setCurrency("EUR");

            $alItem->addItem($item);
        }

        $amount = new Amount();
        $amount->setCurrency("EUR");
        $amount->setTotal(money_format('%!i', $total));

        $transaction = new Transaction();
        $transaction->setDescription("Paiement");
        $transaction->setAmount($amount);
        $transaction->setItemList($alItem);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("https://shop-adrienhuygens.rhcloud.com/succes.php");
        $redirectUrls->setCancelUrl("https://shop-adrienhuygens.rhcloud.com/error.php");

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));

        $payment->create($apiContext);

        $this->referencePaypal = $payment->getId();

        $isNew = !isset($this->id);

        if (!empty($this->produits)) {
            parent::save();
            if ($isNew) {
                $this->saveProduits();
            }
        }

        $tab = $payment->getLinks();

        header("Location: " . $tab[1]->getHref());
    }
    
    function executePayment($payerId,$paymentId){
        $apiContext = $this->getOAuthPaypal();
        
        $payment = Payment::get($paymentId,$apiContext);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        
        
        $payment->execute($paymentExecution,$apiContext);
        
        if($payment->getState()=="approved"){
            $this->paye = true;
            parent::save();
        }
        
        return $this->paye;
    }
    private function saveProduits() {
        $sql = "INSERT INTO commandeproduit VALUES (null,:produitId,:quantite,:prix,:reduction)";

        foreach ($this->produits as $duo) {
            $produit = $duo[0];
            $quantite = $duo[1];

            if ($produit->getReduction() === 0) {
                $reduction = "0";
            } else {
                $reduction = $produit->getReduction();
            }

            $tab = array(
                'produitId' => $produit->getId(),
                'quantite' => $quantite,
                'prix' => $produit->getPrix(),
                'reduction' => $reduction
            );

            Commande::execute($sql, $tab);
        }
    }

    function getAll() {
        return $this->produits;
    }

    private function getIndex(Produit $produit) {
        $i = false;
        foreach ($this->produits as $j => $duo) {
            if ($produit == $duo[0]) {
                $i = $j;
            }
        }
        return $i;
    }

    function getNbProduit() {
        return count($this->produits);
    }

    function getQuantite(Produit $produit) {
        if ($this->getIndex($produit) !== false) {

            return (int) $this->produits[$this->getIndex($produit)][1];
        }
    }
    
    static function existCommandeByPaypalRef($paypalRef){
        $sql = "SELECT COUNT(id) AS nb FROM commande WHERE referencePaypal=:ref";
        $params = array(':ref'=>$paypalRef);
        $retour = Commande::executeAndFetchAll($sql,$params);
        return isset($retour[0]["nb"]) && $retour[0]["nb"]>0;
    }
    
    static function findByPaypalReference($referencePaypal){
        return parent::generalFind($referencePaypal,"referencePaypal");
    }

}
