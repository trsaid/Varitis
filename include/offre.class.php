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
	
}