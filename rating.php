<?php
session_start();
require __DIR__ . '/include/db.php';
require __DIR__ . '/include/item.class.php';
$item = new item();
if(($_POST['id']) && (isset($_SESSION['id_me']))){
    $user = $_POST['userId'];
	$res = $item->Fav($user, $_POST['id']);
	
	$tab = array();
	
	if($res){
		$tab[0] = "Annonce enregistrée.";
		$tab[1] = true;
	}else{
		$tab[0] = "Annonce n'est plus enregistrée.";
		$tab[1] = false;
	}
	
	echo json_encode($tab);
}else{
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
}
?>