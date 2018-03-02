<?php
class offre {
	
	public function faireOffre($id_ann, $prix, $text){
		try {
			$db = DB();
			$query = $db->prepare("INSERT INTO offre(price_offre, com_offre, date_offre, id_offreur, id_ann)
			VALUES (:price_offre, :com_offre, :date_offre, :id_offreur, :id_ann)");
			$query->bindParam("price_offre", $prix, PDO::PARAM_STR);
			$query->bindParam("com_offre", $text, PDO::PARAM_STR);
			// Date actuelle.
			$datePost = date('Y-m-d H:i:s');
			$query->bindParam("date_offre", $datePost, PDO::PARAM_STR);
			// ID de l'utilisateur.
			$id_offreur = $_SESSION['id_me'];
			$query->bindParam("id_offreur", $id_offreur, PDO::PARAM_STR);
			$query->bindParam("id_ann", $id_ann, PDO::PARAM_STR);
			$query->execute();
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function checkOffreur($id_ann){
		try {
			$db = DB();
			$query = $db->prepare("
									SELECT *
									FROM annonce
									WHERE id_ann=:id_ann
									AND id_me=:id_me");
			$query->bindParam("id_ann", $id_ann, PDO::PARAM_STR);
			// ID de l'utilisateur.
			$id_offreur = $_SESSION['id_me'];
			$query->bindParam("id_me", $id_offreur, PDO::PARAM_STR);
			$query->execute();
			
			if ($query->rowCount() > 0){
				return true;
			}
			
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function getOffres($id_ann){
		try {
			$db = DB();
			$membre = new Membre();
			$item = new item();
			$query = $db->prepare("SELECT *
									FROM offre o
										INNER JOIN annonce a
											ON o.id_ann = a.id_ann
									WHERE id_me=:id_me
									AND o.id_ann=:id_ann
									ORDER BY id_off DESC LIMIT 5 ");
			// ID de l'utilisateur.
			$id_me = $_SESSION['id_me'];
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->bindParam("id_ann", $id_ann, PDO::PARAM_STR);
			$query->execute();
			
			$result = array();
			
			if ($query->rowCount() > 0){
				$count = 0;
				while($row = $query->fetch()) {
					$count++;
					$result["nom_offreur" . $count] = '<strong>'.$membre->getUsername($row["id_offreur"]).'</strong>';
					$result["id_offreur" . $count] = $row["id_offreur"];
					$result["price_offre" . $count] = $row["price_offre"];
					$result["date_offre" . $count] = $item->AffDate($row["date_offre"]);
					$result["com_offre" . $count] = $row["com_offre"];
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
	
}