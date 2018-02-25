<?php
session_start();

require __DIR__ . '/include/membre.class.php';
$Membre = new Membre();

// Verification connexion
if(empty($_SESSION['id_me']))
{
	$logged = false;
	$Membre->NotLogged('Action impossible', 'Erreur : Vous devez être connecté pour accéder à cette page.');
}else{
	$length = 12;
	$token = bin2hex(random_bytes($length));
}

require __DIR__ . '/include/item.class.php';
require __DIR__ . '/include/db.php';
$app = new item();

$sell_err = '';
$sell_ok = '';

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Varitis - Vendre un bien</title>
	<!-- Bootstrap & CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	
	<link rel="stylesheet" href="assets/css/style.css" />
	<!-- Navbar -->
	<div class="Navbar">
		<?php include('include/navbar.php'); ?>
	</div>
</head>
<body class="sell">
	<div class="container container-cont">
		<div class="well">
			<h2>
				Créez votre annonce.
			</h2>
			<div id="result"></div>
		</div>
		<form method="POST" enctype="multipart/form-data" id="sellpost">
			<div class="col-lg-6">
				<div class="form-group">
					<label for="">Titre</label>
					<input type="text" name="title-annonce" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="sel1">Catégorie</label>
					<select name="categ-select" class="form-control" id="sel1">
						<option value="categ0">Selectionnez une catégorie</option>
						<?php $app->categList(); ?>
					</select>
				</div> 
			</div>
			<div class="col-lg-8">
				<div class="form-group">
					<label for="desc-annonce">Description de votre annonce</label>
					<textarea class="form-control" rows="5" name="desc-annonce"></textarea>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label for="">Prix</label>
					<div class="input-group">
						<input type="text" name="prix-annonce" class="form-control"/>
						<span class="input-group-addon">€</span>
					</div>
				</div>
			</div>
				
			<div class="col-lg-5">
				<div class="form-group">
					<label for="sel2">Ville</label>
					<select name="ville-select" class="form-control" id="sel2">
						<option value="ville0">Selectionnez une ville</option>
						<?php $app->villeList(); ?>
					</select>
				</div>
			</div>
				<div class="row" id="upload-zone">
					<div class="col-sm-2">
						<div class="card card-info upload-zone" data-up-token="<?php echo $token;?>" style="margin: 1em; text-align: center;">
							<div class="form-group">
								
								<input type="file" name="fileToUpload" id="fileToUpload">
								<label for="fileToUpload" class="label-file">
									<img src="./assets/images/upload.png" class="upload-img" /></br>
									<span class="label-label-file">Choisir une image</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			
				
				<div class="form-group">
					<input type="submit" name="btnSell" id="btn-sell-submit" class="btn btn-primary" onclick="return submitForm()" value="Valider mon annonce"/>
					
				</div>
		</form>
	</div>
	
	<? include 'script.php' ?>
	<script defer src="assets/js/upload.js"></script>
	<script defer src="assets/js/sell.js"></script>
</body>
</html>