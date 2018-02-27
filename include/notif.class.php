<?php
class notif extends item {
	
	public function notif_update($id_offre){
		try {
			$db = DB();
			if($id_offre == 'lu_all'){
				$query = $db->prepare("UPDATE offre SET status = 1 WHERE status = 0 AND id_me=:id");
			}else if($id_offre == 'non_lu_all'){
				$query = $db->prepare("UPDATE offre SET status = 0 WHERE status = 1 AND id_me=:id");
			}else{
				$query = $db->prepare("UPDATE offre SET status = 1  WHERE status = 0 AND id_off=:id_offre AND id_me=:id");
				$query->bindParam("id_offre", $id_offre, PDO::PARAM_STR);
			}
			// ID de l'utilisateur.
			$id_me = $_SESSION['id_me'];
			$query->bindParam("id", $id_me, PDO::PARAM_STR);
			
			$query->execute();
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function show_notif(){
		try {
			$db = DB();
			$membre = new Membre();
			$item = new item();
			// $query = $db->prepare("SELECT * FROM offre WHERE id_ann IN (SELECT id_ann FROM annonce WHERE id_me=:id_me) ORDER BY id_off DESC LIMIT 5 ");
			$query = $db->prepare("SELECT
										*
									FROM
										offre o
											INNER JOIN 
												annonce a
												ON o.id_ann = a.id_ann
									WHERE
										id_me=:id_me
									ORDER BY id_off DESC LIMIT 5 ");
			// ID de l'utilisateur.
			$id_me = $_SESSION['id_me'];
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->execute();
			
			$result = array();
			
			if ($query->rowCount() > 0){
				$count = 0;
				while($row = $query->fetch()) {
					$count++;
					$result["id_offreur" . $count] = '<strong>'.$membre->getUsername($row["id_offreur"]).'</strong> <small>a fait une offre à votre annonce</small>';
					$result["date_offre" . $count] = $item->AffDate($row["date_offre"]);
					$result["titre_ann" . $count] = $item->getTitle($row["id_ann"]);
					// $result["com_offre" . $count] = $row["com_offre"];
				}
					$result["nbr"] = $count;
			} else {
					$result[] = 'Aucune notification.';
			}
			return $result;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	public function count_notif_non_lu(){
		try {
			$db = DB();
			// $query = $db->prepare("SELECT id_off FROM offre WHERE status = 0 AND id_ann IN (SELECT id_ann FROM annonce WHERE id_me=:id_me)");
			$query = $db->prepare("SELECT
										id_off 
									FROM
										offre o 
										INNER JOIN
											annonce a 
											ON o.id_ann = a.id_ann 
									WHERE
										status = 0 
										AND id_me =:id_me");
			// ID de l'utilisateur.
			$id_me = $_SESSION['id_me'];
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->execute();
			
			$result = $query->rowCount();
			
			return $result;
			
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	
}

?>