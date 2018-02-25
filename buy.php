<?php
session_start();

// Connexion à la BDD
require __DIR__ . '/include/db.php';
$db = DB();

// Integration de la class item
require __DIR__ . '/include/item.class.php';
$item = new item();


$res_par_page = 5; // Nombre de résultats par pages.
$max_page = $item->get_taille_list($res_par_page); // Nombre maximum de pages possible.

if (isset($_GET["page"])) { 
	$page  = $_GET["page"];
	if($page > $max_page || !is_numeric($page) || ($page < 1)){
		$item->Introuvable("buy.php");
	}
} else {
	$page = 1; 
};

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
<div class="container">

<?php 
if (isset($_GET['ann'])){
	echo $item->ShowAnn($_GET['ann']);
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
} ?>
</div>
	
	<? include 'script.php' ?>
	 <script defer src="assets/js/offre.js"></script>
	 <script defer src="assets/js/like.js"></script>
	
</body>
</html>