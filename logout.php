<?php
session_start();

if (isset($_SESSION['id_me'])) {
	echo '
	<div class="MsgError">
		Vous avez été déconnecté.<br><br>
		À bientôt!
	</div>';
	unset($_SESSION['id_me']);
	session_unset();
	session_destroy();
	header( "refresh:2;url=index.php" );
	exit;
}
else {
	echo '
	<div class="MsgError">
		Page introuvable.<br><br>
		Redirection en cours...
	</div>';
	header( "refresh:2;url=index.php" );
}
 ?>
