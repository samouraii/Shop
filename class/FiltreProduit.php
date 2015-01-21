<?php

/**
 * Description of FiltreProduit
 *
 * @author Huygens Adrien
 */
class FiltreProduit extends Filtre {

    private $categories = Array();
    private $prixMin;
    private $prixMax;
    private $reductionMin;
    private $reductionMax;
    private $triNom;
    private $triPrix;
    private $triDateAjout;
    private $active = true;
    
    function __construct($tab = null) {
        if(isset($tab) && count($tab)==8){
            $this->addCategories($tab[0]);
            $this->addPrixBetween($tab[1], $tab[2]);
            $this->triPrix($tab[3]);
            $this->triNom($tab[4]);
            $this->triDateAjout($tab[5]);
            $this->addReductionBetween($tab[6], $tab[7]);
        }
    }

    function addCategorie(Categorie $cat) {
        $this->categories[] = $cat;
    }

    function addCategories(Array $cats) {
        $this->categories = array_merge($this->categories, $cats);
    }

    function addPrixBetween($min, $max) {
        if ($min !== "MIN" && $max !== "MAX") {
            $this->prixMin = $min;
            $this->prixMax = $max;
        }
    }

    function addReductionBetween($min, $max) {
        if ($min !== "MIN" && $max !== "MAX") {
            $this->reductionMin = $min;
            $this->reductionMax = $max;
        }
    }

    function addTriPrix($val) {
        if ($val !== 0) {
            $this->triPrix = $this->convert($val);
        }
    }

    function addTriNom($val) {
        if ($val !== 0) {
            $this->triNom = $this->convert($val);
        }
    }

    function addDateAjout($val) {;
        if ($val !== 0) {
            $this->triDateAjout = $this->convert($val);
            
        }
    }

    private function convert($val) {
        switch ($val) {
            case "UP" : $retour = "ASC"; break;
            case "DOWN" : $retour ="DESC"; break;
        }
        return $retour;
    }

    function getSql() {
        $sql = "SELECT * FROM produit";
        $params = array();
        $cat = false;

        if (count($this->categories) > 0) {
            //Ajoute une jointure pour les catégories
            $sql.=" LEFT JOIN categorieproduit ON produit.id  = categorieproduit.produitId LEFT JOIN categorie ON categorieproduit.categorieId = categorie.id";
            $cat = true;
        }
//On s'occupe de la clause where
        $sql.=" WHERE";
        $andWhere = false;

        if ($cat) {
            $sql.=" (categorie.id = :cat0";
            foreach ($this->categories as $i => $categorie) {
                if ($i > 0) {
                    $sql.=" OR categorie.id = :cat" . $i;
                }
                $params = array_merge($params, array("cat$i" => $categorie->getId()));
            }
            $sql.=")";
            $andWhere = TRUE;
        }

        if ($this->prixMax!=null) {
            if ($andWhere) {
                $sql .= " AND";
            }
            $sql.=" prix BETWEEN :prixMin AND :prixMax";
            $params = array_merge($params, array("prixMin" => $this->prixMin, "prixMax" => $this->prixMax));
            $andWhere = true;
        }

        if ($this->reductionMax!=null) {
            if ($andWhere) {
                $sql .= " AND";
            }
            $sql.=" reduction BETWEEN :reductionMin AND :reductionMax";
            $params = array_merge($params, array("reductionMin" => $this->reductionMin, "reductionMax" => $this->reductionMax));
            $andWhere = TRUE;
        }

        if ($andWhere) {
            $sql .= " AND";
        }
        $sql.=" active = :active";
        $params = array_merge($params, array('active' => $this->active));

        //On s'occupe de la clause ORDER BY
        if ($this->triDateAjout || $this->triNom || $this->triPrix) {
            $sql.=" ORDER BY";
            $virgule = false;

            if ($this->triDateAjout) {
                $sql.=" dateAjout ".$this->triDateAjout;
                $virgule = true;
            }

            if ($this->triNom) {
                if ($virgule) {
                    $sql.=",";
                }
                $sql.=" nom ".$this->triNom;
                $virgule = true;
            }

            if ($this->triPrix) {
                if ($virgule) {
                    $sql.=",";
                }
                $sql.=" prix ".$this->triPrix;
                $virgule = true;
            }
        }

        return array($sql, $params);
    }

}
