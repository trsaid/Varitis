<?php
session_start();
// Connexion à la BDD
require __DIR__ . '/include/db.php';
$db = DB();

require __DIR__ . '/include/item.class.php';
$item = new item();

try{
	
	if(isset($_REQUEST['reponse'])){
		
		
		$sql = "SELECT * FROM annonce WHERE titre LIKE :txt";
		
		$query = $db->prepare($sql);
		$txt = $_REQUEST['reponse'] . '%';
		$query->bindParam(":txt", $txt, PDO::PARAM_STR);
		
		$query->execute();
		
		if($query->rowCount() > 0){
			
			while($items = $query->fetch()){
				
				$date_item = strtotime($items['date_item']);
				$pid = $items['id_ann'];
				$ville_id = $items['id_ville'];
				
				$query_ville = $db->prepare("SELECT * FROM ville WHERE id_ville = ". $ville_id);
				$query_ville->execute();
				$ville = $query_ville->fetch();
				$img = $item->ShowImg($pid);
				echo'
					<div class="ann_grp">
						<a href="buy.php?ann='. $pid .'" >
							<li class="list-group-item">
								<div>
									<img src="./uploads/'.$img[0][0].'" class="ann_img" />
								</div>
								<div class="ann_cont">
									<h2 class="ann_titre">'. $items['titre'] .'</h2>
									<p class="ann_ville">'. $ville['Nom_ville'] .'</p>
									<p class="ann_desc">'. $item->textLimit($items['description']) .'</p>
									<p class="ann_date">'. $item->AffDate($date_item ) .'</p>
									<h2 class="ann_price">'. $items['prix'] .' € </h2>
								</div>
							</li>
						</a>
					</div>';
				
			}
			
		} else{
			
			echo "<p>Aucun résultat.</p>";
			
		}
		
	}  
	
} catch(PDOException $e){
	
	exit($e->getMessage());
	
}
?>

