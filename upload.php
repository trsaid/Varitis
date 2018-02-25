<?php
session_start();
require __DIR__ . '/include/item.class.php';
require __DIR__ . '/include/db.php';
$item = new item();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_me'])) {
	if (is_array($_FILES) && empty($_POST["delimg"])) {
		//Upload var
		$target_dir = "uploads/";
		$ext = explode(".", $_FILES["dropImage"]["name"]); //on récup l'extention
		$newFileName = hash('sha256', session_id().microtime()) . '.' . end($ext); //on genère un nom aléatoir sha256.$ext
		$sourcePath = $_FILES['dropImage']['tmp_name'];
		$up_token = $_POST['uptoken'];
		
		$target_file = $target_dir . $newFileName;
	
		$check = getimagesize($_FILES["dropImage"]["tmp_name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$imgLim = $item->UpLim($up_token);
		
		$result = array();
		
		if(($check === false) || ($imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpg" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "JPG")) {
			$result[0] = "Error";
		}elseif ($imgLim > 2) {
			$result[0] = "Error2";
		}elseif (is_uploaded_file($_FILES['dropImage']['tmp_name'])) {
			if (move_uploaded_file($sourcePath, $target_file)) {
				$result[0] = $newFileName;
				$result[1] = $item->tmpUpImage($newFileName, $up_token);
			}
		}
		echo json_encode($result);
	}elseif(!empty($_POST["delimg"])){
		$img_id = $_POST["delimg"];
		echo $item->del_tmpImage($img_id);
	}
}else{
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}
?>