<?php
session_start();
if (isset($_SESSION['id_me']) && isset($_POST["form_message"])) {
	require __DIR__ . '/include/item.class.php';
	require __DIR__ . '/include/membre.class.php';
	require __DIR__ . '/include/offre.class.php';
	require __DIR__ . '/include/db.php';
	$offre = new offre();
	
	$id_ann = $_POST["id_ann"];
	$prix = $_POST["prix-offre"];
	$text = $_POST["form_message"];
	
	$offre->faireOffre($id_ann, $prix, $text);
	
}else{
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

?>