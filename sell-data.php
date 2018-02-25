<?php
session_start();
require __DIR__ . '/include/item.class.php';
require __DIR__ . '/include/db.php';
$app = new item();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$titre = trim($_POST['title-annonce']);
	$desc = trim($_POST['desc-annonce']);
	$prix = trim($_POST['prix-annonce']);
	$ville = trim($_POST['ville-select']);
	$categ = trim($_POST['categ-select']);
	$token = trim($_POST['uptoken']);
	
	$result = array();
	
	if ($titre == "") {
		$result[0] = "1";
		$result[1] = "Veuillez entrer un titre.";
		
	} else if ($categ == "categ0"){
		$result[0] = "2";
		$result[1] = "Veuillez choisir une catégorie.";
		
	} else if ($desc == "") {
		$result[0] = "3";
		$result[1] = "Veuillez entrer la description de votre article.";
		
	} else if ($prix == "") {
		$result[0] = "4";
		$result[1] = "Veuillez entrer le prix de votre article.";
		
	} else if ($ville == "ville0") {
		$result[0] = "5";
		$result[1] = "Veuillez entrer votre code postal ou votre Ville.";
		
	} else {
		$categ = filter_var($categ, FILTER_SANITIZE_NUMBER_INT);
		$ville = filter_var($ville, FILTER_SANITIZE_NUMBER_INT);
		$idr = $app->Sell($titre, $prix, $desc, $ville, $categ); //Retourne le dernier id
		$app->UpImage($token, $idr);
		$result[0] = "6";
		$result[1] = 'Votre Annonce "' . $titre . '" a été mise en ligne.';
		$result[2] = $idr;
	}
	echo json_encode($result);
}else{
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}
?>