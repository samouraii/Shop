<?php 
 ini_set('display_errors','1');
 error_reporting(E_ALL); 
    session_start(); 
//var_dump($_POST);
//var_dump($_SERVER);
    if(isset($_SESSION['panier'])){
        $panier = unserialize(stripslashes($_SESSION['panier']));
    }else{
        $panier = new Commande();
    }

?>

<?php $GLOBALS["dossier"] = "http://" . $_SERVER['SERVER_NAME'] . "/" ?>

<?php include 'vendor/autoload.php'; ?>


<!-- Navigation -->

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>

            </button>
            <?php //echo $_SERVER['SERVER_NAME'];?>		
            <?php //echo $_SERVER['REQUEST_URI'];?>
            <a class="navbar-brand" href="<?php echo$GLOBALS['dossier']; ?>"><img src="<?php echo$GLOBALS['dossier']; ?>fonts/titre.jpg" alt=""/></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo$GLOBALS['dossier']; ?>">Accueil</a>
                </li>
                <li>
                    <a href="<?php echo$GLOBALS['dossier']; ?>catalogue.php">Catalogue</a>
                </li>

                <li>
                    <a href="#">Contact</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mon Panier(<?php echo $panier->getNbProduit(); ?>) <b class="caret"></b> </a> 
                    <ul class="dropdown-menu">
                        <div  class="col-xs-14 col-sm-14 col-lg-14 col-md-14">
                            <div class="thumbnail col-separation">
                                <li><h5>Dernier Article</h5>
                                   
                                    
                                     <?php 
                                     $total=0;
                                     if ($panier->getNbProduit()>0){
                                     foreach( $panier->getAll() as $info)
                                {
                                    $total += $info[1] * $info[0]->getPrix()* (100 - $info[0]->getReduction())/100 ; 
                                    $nom = $info[0]->getNom();
                                }
                                 
                                     
                                     
                                    ?>
                                    <hr>
                                     <?php echo $nom;
                                     
                                     }
                                     else{
                                         echo "<hr> votre panier est vide";
                                     }
                                     
                                     ?>
                                    <br><hr>Total de: <?php echo $total;?> €

                                    <div class="form-group has-success" method="POST" action="panier.php">
                                        <form role="form" method="POST" action="panier.php" class="form-horizontal">


                                            <input type="submit" class="btn btn-default" value="Accerder au Panier"  />

                                        </form>
                                    </div>
                                </li>									
                            </div>	  									
                        </div>                      


                    </ul>
                </li>	  
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Connection <b class="caret"></b> </a> 
                    <ul class="dropdown-menu col-lg-14">
                        <div  class="col-xs-4 col-sm-12 col-lg-12 col-md-12">
                            <div class="form-group has-success">
                                <?php
                                

                                if (isset($_SESSION['username']) && isset($_SESSION['mdp']) && !isset($_GET['deco']) && Personne::verifConnexion($_SESSION['username'], $_SESSION['mdp'])) {
                                    ?>
                                    <form role="form" method="POST" action="?deco=t" class="form-horizontal">

                                        <div class="form-group">

                                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-cloud"></span> Deconnection</button>

                                        </div>
                                    </form>

                                    <?php
                                } elseif (isset($_GET['deco']) && $_GET['deco'] == "t") {
                                    unset($_SESSION['username']);
                                    unset($_SESSION['mpd']);
                                    $page = $_SERVER['PHP_SELF'];
                                     header("Refresh: 0; url=$page");
                                } elseif (isset($_POST['usernames']) && isset($_POST['mdp'])) {
                                    //vérification de la connection

                                    if (Personne::verifConnexion($_POST['usernames'], $_POST['mdp'])) {

                                        $_SESSION['username'] = htmlentities($_POST['usernames']);
                                        $_SESSION['mdp'] = htmlentities($_POST['mdp']);
                                       
                                    }
$page = $_SERVER['PHP_SELF'];
echo ($page);                                    

header("Refresh: 0; url=$page");
                                } else {
                                    ?>

					    <form role="form" method="POST" action="<?php  echo 
"http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];  ?>" class="form-horizontal">
                                        <div class="form-group has-success">
                                            <label for="login" class="control-label">Login</label>
                                            <div class="">
                                                <input class="form-control" name="usernames" id="login" type="text" placeholder="Entrez vote Login"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" >Mot de passe</label>

                                            <input class="form-control" id="password" name="mdp" type="password" placeholder="Entrez vote mot de passe"/>

                                        </div>
                                        <div class="form-group">

                                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-cloud"></span> Connection</button>
                                            <a href="./inscription.php" alt="inscription"> Inscription </a>


                                        </div>
                                    </form>


    <?php
}
?>    
                            </div>
                        </div>
                </li>
            </ul>
        </div>
        </li>

        </ul>
    </div>


    <!-- /.navbar-collapse -->
</div>
<!-- /.container -->
</nav>
<?php
if(isset($_SESSION['e'])){
    
    ?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  
  <span class="sr-only">Error:</span>
  <?php echo $_SESSION['e'];
  unset($_SESSION['e']);
  ?>
</div>
<?php
  
}
if(isset($_SESSION['s'])){
    
    ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  
  <span class="sr-only">Success:</span>
  <?php echo $_SESSION['s']; ?>
</div>
<?php
  unset($_SESSION['s']);
}

?>
