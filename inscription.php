
<?php
foreach (glob('class/*.php') as $include) {
    include ($include);
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>

        <meta charset="utf-8">
        <meta name="description" content="Site de Ecommerce">
        <meta name="author" content="Huygens Adrien">

        <title>Shop Homepage - Start Bootstrap Template</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/shop-homepage.css" rel="stylesheet">


    </head>

    <body>

        <?php include 'pagination/Header.php'; ?>

        <div class="container" id="container">

            <div class="row">
                <?php
                if (isset($_GET['inscription']) && $_GET['inscription'] == "inc") {
                    try {
                        $user = new Personne();
                        $erreur = 0;

                        $tab = array("mail", "username", "civilite", "nom", "prenom", "dateDeNaissance"); //, "adresse", "codePostal", "ville", "pays");


                        if (isset($_POST["mdp"]) && isset($_POST["mdp2"])) {
                            $user->setMdp($_POST["mdp"], $_POST["mdp2"]);
                        }


                        foreach ($tab as $info) {
                            if (isset($_POST[$info])) {
                                $nom = "set" . ucfirst($info);
                                $user->$nom($_POST[$info]);
                            }
                        }
                        $tab = array("adresse", "codePostal", "ville", "pays");
                        foreach ($tab as $info) {
                            if (isset($_POST[$info])) {
                                
                            } else {
                                $erreur = 2;
                            }
                        }
                        if ($erreur < 1) {

                            $ville = new Ville();
                            $pays = new Pays();
                            $adress = new Adresse();

                            $ville->setCodePostal(htmlentities($_POST['codePostal']));
                            $ville->setNom(htmlentities($_POST['ville']));

                            $pays->setNom(htmlentities($_POST['pays']));

                            $adress->setRue(htmlentities($_POST['adresse']));
                            $adress->setPays($pays);
                            $adress->setVille($ville);

                            $user->setAdresse($adress);

                            $user->save();
                            $msg = "bonjour,"
                                    . "<br/> Pour valider votre compte veuillez cliquez sur le lien suivant:"
                                    . "<a href='http://shop-adrienhuygens.rhcloud.com/traitement.php?activ=".$user->getToken()."&username=".$user->getUsername()."'>ici </a>";
                            $titre = "test";
                            
                                              
                         
                         
                         
                          Message::sendEmail($msg, $user->getMail(), $titre,"");
                          
                        }
                    } catch (Exception $e) {
                        Message::messages($e->getMessage());
                        echo $e->getMessage();
                        //header("Refresh: 0; url=inscription.php");
                    }
					
                }
                    //echo "test";
                    ?>
                    <div class="col-lg-8">
                        <form role="form" class="form-horizontal" action="inscription.php?inscription=inc" method="POST">
                            <div class="form-group">
                                <label for="mail" class="col-lg-4 control-label">Email</label>
                                <div class="col-lg-8">
                                    <input class="form-control" value="<?php if(isset($_POST['mail']))echo $_POST['mail'];?>" name="mail" id="mail" type="text" placeholder="Entrez vote email"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mdp" class="col-lg-4 control-label">Mot de passe</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="mdp" name="mdp"type="password" placeholder="Entrez vote mot de passe"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mdp2" class="col-lg-4 control-label">Confirmation du mot de passe</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="mdp2" name="mdp2" type="password" placeholder="Entrez vote email"/>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label for="username" class="col-lg-4 control-label">Username</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="username" value="<?php if(isset($_POST['username']))echo $_POST['username'];?>" name="username" type="text" placeholder="Entrez votre Login"/>
                                </div>
                            </div>        
                            <div class="form-group">
                                <label for="optionsRadios2" class="col-lg-4 control-label">Civilité </label>
                                <div class="col-lg-8">
                                    <div class="radio">
                                        <label>
                                            <input name="civilite" id="optionsRadios2" type="radio" value="M" checked >
                                            M.</input>
                                        </label>

                                        <label>
                                            <input name="civilite" id="optionsRadios2" type="radio" value="Mlle">
                                            mlle</input>
                                        </label>

                                        <label>
                                            <input name="civilite" id="optionsRadios2" type="radio" value="Mme">
                                            Mme</input>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Nom" class="col-lg-4 control-label">Nom</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="Nom" name="nom" value="<?php if(isset($_POST['nom']))echo $_POST['nom'];?>" type="text" placeholder="Entrez vote nom"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Prenom" class="col-lg-4 control-label">Prenom</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="Prenom" name="prenom"value="<?php if(isset($_POST['prenom']))echo $_POST['prenom'];?>" type="text" placeholder="Entrez vote prenom"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date" class="col-lg-4 control-label">Date de naissance</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="dateDeNaissance"id="date" value="<?php if(isset($_POST['dateDeNaissance']))echo $_POST['dateDeNaissance'];?>"type="text" placeholder="Entrez vote date de naissance"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adr" class="col-lg-4 control-label">adresse</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="adr" name="adresse" value="<?php if(isset($_POST['adresse']))echo $_POST['adresse'];?>" type="text" placeholder="Entrez votre adresse"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-lg-4 control-label">Code Postal</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="code" name="codePostal" value="<?php if(isset($_POST['codePostal']))echo $_POST['codePostal'];?>" type="text" placeholder="Entrez vote codePostal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vile" class="col-lg-4 control-label">Ville</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="ville"  value="<?php if(isset($_POST['ville']))echo $_POST['ville'];?>"name ="ville" type="text" placeholder="Entrez vote ville"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pays" class="col-lg-4 control-label">Pays</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="pays" name="pays" value="<?php if(isset($_POST['pays']))echo $_POST['pays'];?>" type="text" placeholder="Entrez vote pays"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-4">
                                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-cloud"></span> Envoyer</button>
                                </div>
                            </div>
                        </form>
                        <?php
                     //fermeture du else
                    ?>
                </div> <!-- col -->


            </div>
            <!-- Fin du carrousell -->
        </div>
        <?php
        include 'pagination/footer.php';
        ?>





    </body>

</html>
