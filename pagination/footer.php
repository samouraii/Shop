        <!-- Footer -->
	<div class="container">
        <footer>
            <div class="row">
				
                <div class="col-lg-16">
					
						<p>Suivez nous sur <a href="http://www.facebook.com">facebook</a><a href="http://www.twitter.com">Twitter</a> </p>
						<p>Copyright &copy; Huygens Inc 2014-2015. All Rights Reserved <a href="../reglement.php" alt=""> Réglement</a> </p>
					
				</div>
            </div>
        </footer>

    </div>
    <!-- jQuery Version 1.11.0 -->
    <script src="<?php echo$GLOBALS['dossier']; ?>js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo$GLOBALS['dossier']; ?>js/bootstrap.min.js"></script>
	
<?php
if(isset($panier))
{
    $_SESSION['panier'] = serialize($panier);
} 
  unset($_SESSION['e']);


?>
