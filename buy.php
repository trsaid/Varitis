<?php
session_start();

// Connexion à la BDD
require __DIR__ . '/include/db.php';
$db = DB();

// Integration de la class item
require __DIR__ . '/include/item.class.php';
$item = new item();
require __DIR__ . '/include/membre.class.php';
require __DIR__ . '/include/offre.class.php';


$res_par_page = 5; // Nombre de résultats par pages.
$max_page = $item->get_taille_list($res_par_page); // Nombre maximum de pages possible.

if (isset($_GET["page"])) { 
	$page  = $_GET["page"];
	if($page > $max_page || !is_numeric($page) || ($page < 1)){
		$item->Introuvable("buy.php");
	}
} else {
	$page = 1; 
}

$max_pagination = 4;
$start_page = ($page-1) * $res_par_page; // Page de départ.

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="assets/icon.ico" />

	<title>Liste des annonce</title>
	
	<!-- Bootstrap & CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

	
	<link rel="stylesheet" href="assets/css/style.css" />
	<!-- Navbar -->
	<div class="Navbar">
		<?php include('include/navbar.php'); ?>
	</div>
</head>
<body class="buy">
	<div class="container" style="overflow: hidden">
	
	<?php 
		if (isset($_GET['ann'])){
			
			$id_ann = $_GET['ann'];
			$items = $item->ShowAnn($id_ann);
			
			$date_item = strtotime($items['date_item']);
			$pid = $items['id_ann'];
			$img = $item->ShowImg($id_ann);
			$nbImg = $img['nb'] - 1;
			$membre = new Membre();
			
	?>
			<li class="list-group-item">
				<div class="big_ann_img">
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
										echo '<div class="carousel-item active">
														<img id="ImageOnSlider" class="d-block img-fluid" src="./uploads/' . $img[$i][0] .'" alt="Image principale">
													</div>';
									} else {
										echo '<div class="carousel-item">
														<img id="ImageOnSlider" class="d-block img-fluid" src="./uploads/' . $img[$i][0] .'">
													</div>';
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
			<div class="row">
			
				<div class="ann_cont">
					<h2 class="ann_titre"><?php echo $items['titre'] ?></h2>
					<p class="ann_date_in">Publié <?php echo $item->AffDate($date_item) ?></p>
					
					<div class="divider"></div>
					<h2 class="ann_price">Prix : <?php echo $items['prix'] ?> €</h2>
					<div class="ann_ville_grp">
						<p class="ann_ville">
							<i class="fas fa-map-marker-alt ville_marker"></i>
							Ville : <?php echo $item->getVille($items['id_ville']) ?>
						</p>
					</div>
					<div class="description">
						<p class="ann_desc_title">Description</p>
						<div class="divider"></div>
						<p class="ann_desc_in"> </br><?php echo $items['description'] ?> </p>
					</div>
				</div>
				<div class='card card-profile text-center'>
					<div class='card-block'>
						<h4 class='card-title'>
							<?php echo $membre->getUsername($items['id_me']); ?>
							<small>Note utilisateur ?</small>
						</h4>
						<img alt='' class='card-img-profile' src='assets/images/user.png'>
						<div class='card-links'>
							<button class="btn btn-primary btn-lg offre_button">Faire une offre</button>
						</div>
					</div>
				</div>
			</div>
			</li>
			<form method="post" class="offre-form">
						<div class="offreErr"></div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label>Entrez votre prix</label>
							<div class="input-group">
								<input type="text" name="prix-offre" data-ann-id="<?php echo $id_ann; ?>" class="form-control prix-offre" required="required"/>
								<span class="input-group-addon">€</span>
							</div>
							<div class="help-block with-errors"></div>
						</div>
					</div>
					</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="form_message">Message</label>
							<textarea name="form_message" class="form-control message-offre" placeholder="Accompagnez votre offre d'un message." rows="4" required="required"></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<input type="submit" name="post" id="post" class="btn btn-success btn-send" value="Validé mon offre">
					</div>
				</div>
			</form>
			
			<?php $offre = new offre();
				$offre_list = $offre->getOffres($id_ann);
				$nbr = $offre_list["nbr"];
				for($i = 1; $i <= $nbr; $i++ ){
					$user_img = $membre->getUserImg($offre_list["id_offreur".$i]);
					echo '<div class="row offre_list">
							
								<img alt="" class="img-profile-offre" src="'.$user_img.'">'.
									'<a class="offre-user">'.$offre_list["nom_offreur".$i].'</a>'.
									'<span class="offre-prix">'.$offre_list["price_offre".$i].'€</span></br>'.
									'<p class="offre-com">'.$offre_list["com_offre".$i] .'</p>'.
									'<a class="offre-date">'. $offre_list["date_offre".$i].'</a>'.
								'
							</div>';
				}
			?>
		
	<?php
		}
		else{
	?>
			<div class="jumbotron">
				<div class="col-sm-8 mx-auto">
					<h1 class="title-page">Liste des annonce</h1>
				</div>
			</div>
			<ul class="list-group">
				<?php $item->ShowList($start_page, $res_par_page); ?>
			</ul>
			<nav aria-label="Liste des pages">
				<ul class="pagination">
					<?php
					$page_preced	= $page - 1; // Page précédente.
					$page_suiv		= $page + 1; // Page suivante.
					
					// Premier objet de la pagination.
					if(($page - 4) < 1){
						$first_page = 1;
					}else{
						$first_page = $page - 4;
					}
					// Toujours afficher 9 pages.
					if($page < 5){
						$max_pagination = 9 - $page;
					}
					// Dernier objet de la pagination.
					if (($page + $max_pagination) <= $max_page){
						$max_page_list = $page + $max_pagination;
					}else{
						$max_page_list = $max_page;
					}
		
					// Affichage de la pagination.
					if($page > 1){
						echo '<li class="page-item"><a class="page-link" href="?page=1"> <i class="fas fa-angle-double-left "></i> </a></li>';
						echo '<li class="page-item"><a class="page-link" href="?page='.$page_preced.'"> <i class="fas fa-angle-left"></i> </a></li>';
					}
					for ($i = $first_page; $i <= $max_page_list ; $i++) {
						if($i == $page){
							echo '<li class="page-item active"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
						}else{	
							echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
						}
					}
					if($page < $max_page){
						echo '<li class="page-item"><a class="page-link" href="?page='.$page_suiv.'"> <i class="fas fa-angle-right"></i> </a></li>';
						echo '<li class="page-item"><a class="page-link" href="?page='.$max_page.'"> <i class="fas fa-angle-double-right "></i> </a></li>';
					}
					?>
				</ul>
			</nav>
	<?php 
		}
	?>
	</div>
	
	<? include 'script.php' ?>
	 <script defer src="assets/js/offre.js"></script>
	 <script defer src="assets/js/like.js"></script>
	
</body>
</html>