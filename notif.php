<?php
session_start();
if (isset($_SESSION['id_me']) && isset($_POST["notif"])) {
	require __DIR__ . '/include/item.class.php';
	require __DIR__ . '/include/membre.class.php';
	require __DIR__ . '/include/notif.class.php';
	require __DIR__ . '/include/db.php';
	$notif = new notif();
	
	$notif_data = $_POST["notif"];
	
	// Toutes les notifications sont notées comme lue.
	if ($notif_data == 'lu_all') {
		$notif->notif_update('lu_all');
	} // Sinon on marque la notif X comme lue.
	elseif (ctype_digit($notif_data)){
		$notif->notif_update($notif_data);
	} // Sinon on envoie la liste de toutes les notif
	else {
		$list_notif = $notif->show_notif();
		
		$count    = $notif->count_notif_non_lu();
		$data     = array(
			'notification' => $list_notif,
			'unseen_notification' => $count
		);
		echo json_encode($data);
	}
}else{
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

?>