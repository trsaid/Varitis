<?php
session_start();

require __DIR__ . '/include/membre.class.php';
require __DIR__ . '/include/db.php';
$app = new Membre();

$register_err_msg = '';

// Verif inscription
if (!empty($_POST['btnRegister'])) {
	if ($_POST['name'] == "") {
		$register_err_msg = 'Veuillez entrer votre nom.';
	} else if ($_POST['email'] == "") {
		$register_err_msg = 'Veuillez entrer une adresse Email.';
	} else if ($_POST['username'] == "") {
		$register_err_msg = 'Veuillez entrer un pseudo.';
	} else if ($_POST['password'] == "") {
		$register_err_msg = 'Veuillez entrer un mot de passe.';
	} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$register_err_msg = 'Adresse Email invalide.';
	} else if ($app->isEmail($_POST['email'])) {
		$register_err_msg = 'Cette adresse Email est déjà utilisée.';
	} else if ($app->isUsername($_POST['username'])) {
		$register_err_msg = 'Ce pseudo est déjà utilisé.';
	} else {
		$id_me = $app->Register($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password']);

		$_SESSION['id_me'] = $id_me;
		header("Location: profile.php");
	}
}
?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="assets/icon.ico" />
	
	<title>Varitis - Inscription</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	
	
	<link rel="stylesheet" href="assets/css/style.css" />
	<!-- Navbar -->
	<div class="Navbar">
		<?php include('include/navbar.php'); ?>
	</div>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-5 well">
			<h4>Inscription</h4>
			<?php
			if ($register_err_msg != "") {
				echo '<div class="alert alert-danger"><strong>Erreur : </strong> ' . $register_err_msg . '</div>';
			}
			?>
			<form action="" method="post">
				<div class="form-group">
					<label for="">Nom</label>
					<input type="text" name="name" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="">Email</label>
					<input type="email" name="email" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="">Pseudo</label>
					<input type="text" name="username" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="">Mot de passe</label>
					<input type="password" name="password" class="form-control"/>
				</div>
				<div class="form-group">
					<input type="submit" name="btnRegister" class="btn btn-primary" value="Inscription"/>
				</div>
			</form>
		</div>
	</div>
	
	
	<? include 'script.php' ?>
	
</body>
</html>