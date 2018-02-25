<?php
class item {
	public function Sell($titre, $prix, $desc, $ville, $categ)
	{
		try {
			$db = DB();
			$query = $db->prepare("INSERT INTO annonce(titre, prix, description, date_item, id_me, id_ville, categorie)
			VALUES (:titre,:prix,:description,:dateItem,:id_me,:id_Ville,:categorie)");
			$query->bindParam("titre", $titre, PDO::PARAM_STR);
			$query->bindParam("prix", $prix, PDO::PARAM_STR);
			$query->bindParam("description", $desc, PDO::PARAM_STR);
			$datePost = date('Y-m-d H:i:s');
			$query->bindParam("dateItem", $datePost, PDO::PARAM_STR);
			$id_me = $_SESSION['id_me'];
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->bindParam("id_Ville", $ville, PDO::PARAM_STR);
			$query->bindParam("categorie", $categ, PDO::PARAM_STR);
			$query->execute();
			return $db->lastInsertId();
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function getTitle($id){
		try {
			$db = DB();
			$query = $db->prepare("SELECT titre FROM annonce WHERE id_ann=:id");
			$query->bindParam("id", $id, PDO::PARAM_STR);
			$query->execute();
			
			$result = $query->fetch();
			
			return $result['titre'];
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function categList(){
		try {
			$db = DB();
			$query = $db->prepare("SELECT * FROM categorie ORDER BY id_cat");
			$query->execute();
			if ($query->rowCount() > 0){
				while($row = $query->fetch()) {
					$i = $row["id_cat"];
					echo '<option value="categ'.$i.'">'. $row["libelle"] .'</option>';
				}
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	public function villeList(){
		try {
			$db = DB();
			$query = $db->prepare("SELECT * FROM ville ORDER BY id_ville");
			$query->execute();
			if ($query->rowCount() > 0){
				while($row = $query->fetch()) {
					$i = $row["id_ville"];
					echo '<option value="ville'.$i.'">'. $row["Nom_ville"] .'</option>';
				}
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function textLimit($text)
	{
		$length = 256;
		if(strlen($text) > $length)
		{
			$textLimit = substr($text,0,$length).'...';
			return $textLimit;
		}else{
			return $text;
		}
	}
	// Argument : $nbr --> Nombre de résultat par page
	// Retourne : $total_pages --> Nombre total des annonces divisé par $nbr le tout arrondit au sup.
	public function get_taille_list($nbr){
		try{
			$db = DB();
			$query = $db->prepare("SELECT COUNT(id_ann) AS total FROM annonce");
			$query->execute();
			$result = $query->fetch();
			$total_pages = ceil($result["total"] / $nbr); //Arrondit au nombre supérieur.
			
			return $total_pages;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function ShowList($min, $max){
		try {
			$db = DB();
			$query = $db->prepare("SELECT * FROM annonce LIMIT $min, $max");
			$query->execute();

			// On affiche chaque entrée une à une
			while ($items = $query->fetch())
			{
				$date_item = strtotime($items['date_item']);
				$pid = $items['id_ann'];
				$ville_id = $items['id_ville'];
				
				$query_ville = $db->prepare("SELECT * FROM ville WHERE id_ville = ". $ville_id);
				$query_ville->execute();
				$ville = $query_ville->fetch();
				$img = self::ShowImg($pid);
				echo'
				<div class="ann_grp">
					<a href="?ann='. $pid .'" >
						<li class="list-group-item">
							<div>
								<img src="./uploads/'.$img[0][0].'" class="ann_img" />
							</div>
							<div class="ann_cont">
								<h2 class="ann_titre">'. $items['titre'] .'</h2>
								<p class="ann_ville">'. $ville['Nom_ville'] .'</p>
								<p class="ann_desc">'. self::textLimit($items['description']) .'</p>
								<p class="ann_date">Le '. date( 'd M Y à H:i.', $date_item ) .'</p>
								<h2 class="ann_price">'. $items['prix'] .' € </h2>
							</div>
						</li>
					</a>
					<div class="like">';
				if(isset($_SESSION['id_me'])){
					$id_me = $_SESSION['id_me'];
					if ($this->isFav($id_me, $pid)){
						echo'<span class="fas fa-heart fa-2x likeBtn'.$pid.' active" onClick="cwRating('.$pid.','.$id_me.')"></span>';
					}else{
						echo'<span class="far fa-heart fa-2x likeBtn'.$pid.'" onClick="cwRating('.$pid.','.$id_me.')"></span>';
					}
				}else{
					echo'<span class="far fa-heart fa-2x likeBtn'.$pid.'" onClick=""></span>';
				}
				
				echo'
						<span class="counter" id="like_count'.$pid.'"></span>
					</div>
				</div>';
				
			}	
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	public function UpImage($token, $idAnn)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT tmp_up_filename FROM tmp_image_upload WHERE tmp_token=:token");
			$query->bindParam("token", $token, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetchAll();
			foreach($result as $fileName){
				$img = $db->prepare("INSERT INTO image_upload(up_filename, up_user, up_annonce) VALUES (:up_filename,:up_user,:up_annonce) ");
				$img->bindParam("up_filename", $fileName['tmp_up_filename'], PDO::PARAM_STR);
				$id_me = $_SESSION['id_me'];
				$img->bindParam("up_user", $id_me, PDO::PARAM_STR);
				$img->bindParam("up_annonce", $idAnn, PDO::PARAM_STR);
				$img->execute();
			}
		} catch (PDOException $e){
			exit($e->getMessage());
		}
	}
	public function tmpUpImage($fileName, $up_token)
	{
		try {
			$db = DB();

			$query = $db->prepare("INSERT INTO tmp_image_upload(tmp_up_filename, tmp_up_user, tmp_token) VALUES (:up_filename,:up_user,:tmp_token) ");

			$query->bindParam("up_filename", $fileName, PDO::PARAM_STR);
			$id_me = $_SESSION['id_me'];
			$query->bindParam("up_user", $id_me, PDO::PARAM_STR);
			$query->bindParam("tmp_token", $up_token, PDO::PARAM_STR);
			$query->execute();
			
			return $db->lastInsertId();

		} catch (PDOException $e){
			exit($e->getMessage());
		}
	}
	public function del_tmpImage($id_tmp){
		try {
			$db = DB();
			$id_me = $_SESSION['id_me'];
			$query = $db->prepare("DELETE FROM tmp_image_upload WHERE tmp_up_id=:id_tmp AND tmp_up_user=:id_me");
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->bindParam("id_tmp", $id_tmp, PDO::PARAM_STR);
			$query->execute();
			
			return true;
			
		} catch (PDOException $e){
			exit($e->getMessage());
		}
	}
	public function UpLim($token){
		try {
			$db = DB();
			$query = $db->prepare("SELECT * FROM tmp_image_upload WHERE tmp_token=:token");
			$query->bindParam("token", $token, PDO::PARAM_STR);
			$query->execute();
			
			$up_num = $query->rowCount();
			
			return $up_num;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	public function ShowImg($idAnn){
		try {
			$db = DB();

			$query = $db->prepare("SELECT up_filename FROM image_Upload WHERE up_annonce=:idAnn");
			$query->bindParam("idAnn", $idAnn, PDO::PARAM_STR);
			$query->execute();
			$nb = $query->rowCount();
			if ($nb > 0) {
				$result = $query->fetchAll();
				$result['nb'] = $nb;
				return $result;
			} else {
				$result['nb'] = 1;
				$result[0][0] = "aucune.png";
				return $result;
			}

		} catch (PDOException $e){
			exit($e->getMessage());
		}
	}
	
	public function ShowAnn($id_ann)
	{
		try {
			$db = DB();
			
			$query = $db->prepare("SELECT * FROM annonce WHERE id_ann=:id_ann");
			$query->bindParam("id_ann", $id_ann, PDO::PARAM_STR);
			$query->execute();
			$items = $query->fetch();
			
			$query = $db->prepare("SELECT COUNT(*) AS total FROM annonce WHERE id_ann=:id_ann"); 
			$query->bindParam("id_ann", $id_ann, PDO::PARAM_STR);
			$query->execute();
			$verif= $query->fetchObject();
			
			if ($verif->total > 0){
			
				$date_item = strtotime($items['date_item']);
				$pid = $items['id_ann'];
				$img = self::ShowImg($id_ann);
				$nbImg = $img['nb'] - 1;
			?>
					<li class="list-group-item">
						<h2 class="ann_titre"> <?php echo $items['titre']; ?></h2>
						<div class="big_ann_img">
							<p>Publié le <?php echo date( 'd M Y à H:i.', $date_item ) ?></p>
							<div id="ImageSlider" class="carousel slide" data-ride="carousel">
								<ol class="carousel-indicators">
							<?php
							for ($i=0; $i <= $nbImg; $i++) {
								if ($i == 0){
									echo '<li data-target="#ImageSlider" data-slide-to="'.$i.'" class="active"></li>';
								} else {
									echo '<li data-target="#ImageSlider" data-slide-to="'.$i.'"></li>';
								}
							}
							?>
								</ol>
								<div class="carousel-inner" role="listbox">
							<?php
							for ($i=0; $i <= $nbImg ; $i++) {
								if ($i == 0){
									echo'
									<div class="carousel-item active">
										<img id="ImageOnSlider" class="d-block img-fluid" src="./uploads/' . $img[$i][0] .'" alt="Image principale">
									</div>
									';
								} else {
									echo '
									<div class="carousel-item">
										<img id="ImageOnSlider" class="d-block img-fluid" src="./uploads/' . $img[$i][0] .'">
									</div>
									';
								}
							}
							?>
							
							</div>
							<a class="carousel-control-prev" href="#ImageSlider" role="button" data-slide="prev">
								<span class="fa fa-chevron-left"  title="Précédent"></span>
							</a>
							<a class="carousel-control-next" href="#ImageSlider" role="button" data-slide="next">
								<span class="fa fa-chevron-right"  title="Suivant"></span>
								
							</a>
						</div>
							
						</div>
						<div class="ann_cont">
							<ul class="item_info">
								<li class="list-group-item">
									<h2 class="ann_price">Prix : <?php echo $items['prix'] ?> € </h2>
									<button class="btn btn-primary btn-lg item-offer" type="submit">Faire une offre</button>
								</li>
								<li class="list-group-item">
									<p class="ann_ville">Ville : <?php echo $items['id_ville'] ?></p>
								</li>
							</ul>
							<p>Description : </p>
							<p class="ann_desc"> <?php echo $items['description'] ?> </p>
						</div>
					</li>
			<?php
			}
			else {
				echo 'Page introuvable.';
			}
			
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}


	public function Fav($userId, $id){
		try {
			$db = DB();
			if(self::isFav($userId, $id)){
				$query = $db->prepare("DELETE FROM etre_favoris WHERE id_me=:id_me AND id_ann=:id_ann");
				$query->bindParam("id_me", $userId, PDO::PARAM_STR);
				$query->bindParam("id_ann", $id, PDO::PARAM_STR);
				$query->execute();
				
				return false;
			}else{
				$date = date("Y-m-d H:i:s");
				$query = $db->prepare("INSERT INTO etre_favoris(date_fav, id_me, id_ann) VALUES (:date_fav, :id_me, :id_ann)");
				$query->bindParam("date_fav", $date, PDO::PARAM_STR);
				$query->bindParam("id_me", $userId, PDO::PARAM_STR);
				$query->bindParam("id_ann", $id, PDO::PARAM_STR);
				$query->execute();
				
				return true;
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
		
	}
	
	public function isFav($userId, $id){
		try {
			$db = DB();
			$query = $db->prepare("SELECT * FROM etre_favoris WHERE id_me=:id_me AND id_ann=:id_ann");
			$query->bindParam("id_me", $userId, PDO::PARAM_STR);
			$query->bindParam("id_ann", $id, PDO::PARAM_STR);
			$query->execute();
			
			$like_num = $query->rowCount();
			
			return $like_num>0?TRUE:FALSE;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function Introuvable($page)
	{
		echo '
		<div class="container errBlock"></div>
		<div class="container errMsg">
					<h1>Page introuvable</h1>      
					<p>La page que vous avez demandé est introuvable.</p>   
					<p>Vous allez être redirigé automatiquement.</p>   
			</div>
		';
		header( "refresh:2;url=". $page ."");
	}
	
	public function AffDate($date){
		if(!ctype_digit($date))
			$date = strtotime($date);
		if(date('Ymd', $date) == date('Ymd')){
			$diff = time()-$date;
		if($diff < 60) /* moins de 60 secondes */
			return 'Il y a '.$diff.' sec';
		else if($diff < 3600) /* moins d'une heure */
			return 'Il y a '.round($diff/60, 0).' min';
		else if($diff < 10800) /* moins de 3 heures */
			return 'Il y a '.round($diff/3600, 0).' heures';
		else /*  plus de 3 heures ont affiche ajourd'hui à HH:MM:SS */
			return 'Aujourd\'hui à '.date('H:i:s', $date);
		}
		else if(date('Ymd', $date) == date('Ymd', strtotime('- 1 DAY')))
			return 'Hier à '.date('H:i:s', $date);
		else if(date('Ymd', $date) == date('Ymd', strtotime('- 2 DAY')))
			return 'Il y a 2 jours';
		else
			return 'Le '.date('d/m/Y à H:i:s', $date);
	}
}
?>