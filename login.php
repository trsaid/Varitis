<?php
session_start();

if(isset($_SESSION['id_me']))
{
	header("Location: index.php");
}

require __DIR__ . '/include/membre.class.php';
require __DIR__ . '/include/db.php';
$app = new Membre();

$login_err_msg = '';

// Verif login
if (!empty($_POST['btnLogin'])) {

	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if ($username == "") {
		$login_err_msg = 'Veuillez entrer un pseudo.';
	} else if ($password == "") {
		$login_err_msg = 'Veuillez entrer votre mot de passe.';
	} else {
		$id_me = $app->Login($username, $password); // check user login
		if($id_me > 0)
		{
			$_SESSION['id_me'] = $id_me; // Set Session
			header("Location: profile.php"); // Redirect user to the profile.php
		}
		else
		{
			$login_err_msg = 'Pseudo ou mot de passe incorrect.';
		}
	}
}
?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="assets/icon.ico" />
	
	<title>Varitis - Connexion</title>
	
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
		<div class="col-md-3"></div>
		<div class="col-md-6 well">
			<h4>Connexion</h4>
			<?php
			if ($login_err_msg != "") {
				echo '<div class="alert alert-danger"><strong>Erreur : </strong> ' . $login_err_msg . '</div>';
			}
			?>
			<form action="" method="post">
				<div class="form-group">
					<label for="">Pseudo/Email</label>
					<input type="text" name="username" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="">Mot de passe</label>
					<input type="password" name="password" class="form-control"/>
				</div>
				<div class="form-group">
					<input type="submit" name="btnLogin" class="btn btn-primary" value="Connexion"/>
				</div>
			</form>
			<hr>
			<label for="">Toujours pas inscrit ?</label>
			<div class="input-group">
				<button class="btn btn-outline-success" type="button" onclick="location.href='inscription.php';">Inscrivez-vous dès maintenant</button>
			</div>
		</div>
	</div>
</div>


<? include 'script.php' ?>
</body>
</html>