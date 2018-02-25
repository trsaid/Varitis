<?php
session_start();

// Verification connexion
if(empty($_SESSION['id_me']))
{
	header("Location: index.php");
}

// Connexion à la BDD
require __DIR__ . '/include/db.php';
$db = DB();

// Integration de la class membre
require __DIR__ . '/include/membre.class.php';
$app = new Membre();

$user = $app->UserDetails($_SESSION['id_me']); // On récupere les infos de l'utilisateur

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="assets/icon.ico" />
	
	<title>Profile</title>
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

	
	<link rel="stylesheet" href="assets/css/style.css" />
	<!-- Navbar -->
	<div class="Navbar">
		<?php include('include/navbar.php'); ?>
	</div>
</head>
<body class="account">	
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<ul class="nav nav-pills flex-column admin-menu">
					<li class="nav-item">
						<a class="nav-link active" data-target-id="profile" href=""><i class="fas fa-user"></i> Profile</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-target-id="change-password" href=""><i class="fas fa-lock"></i> Mot de passe</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-target-id="settings" href=""><i class="fas fa-cogs"></i> Configuration</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-target-id="logout" href=""><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
					</li>
				</ul>
			</div>
			<div class="col-md-9 admin-content" id="profile">
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Nom</h3>
					</div>
					<div class="card-block">
						<?php echo $user->name; ?>
					</div>
				</div>
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Email</h3>
					</div>
					<div class="card-block">
						<?php echo $user->email ?>
					</div>
				</div>
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Dernière modification du mot de passe</h3>
					</div>
					<div class="card-block">
						...
					</div>
				</div>
			</div>
			<div class="col-md-9 admin-content" id="settings">
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Notification</h3>
					</div>
					<div class="card-block">
						<div class="label label-success">
							Oui/non
						</div>
					</div>
				</div>
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Type de compte</h3>
					</div>
					<div class="card-block">
						<div class="tag tag-success">
							Admin/Utilisateur..
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9 admin-content" id="change-password">
				<h4>Changer mon mot de passe</h4>
				<?php
				// if ($register_err_msg != "") {
					// echo '<div class="alert alert-danger"><strong>Erreur : </strong> ' . $register_err_msg . '</div>';
				// }
				?>
				<form action="" method="post">
					<div class="form-group">
						<label for="">Mot de passe actuel</label>
						<input type="password" name="password" class="form-control"/>
					</div>
					<div class="form-group">
						<label for="">Nouveau mot de passe</label>
						<input type="password" name="password" class="form-control"/>
					</div>
					<div class="form-group">
						<label for="">Confirmation du MDP</label>
						<input type="password" name="password" class="form-control"/>
					</div>
					<div class="form-group">
						<input type="submit" name="btnLPw" class="form-control btn btn-primary" value="Valider"/>
					</div>
				</form>
			</div>
			<div class="col-md-9 admin-content" id="logout">
				<div class="card card-info" style="margin: 1em;">
					<div class="card-header">
						<h3 class="card-title">Déconnexion</h3>
					</div>
					<div class="card-block">
						Voulez-vous vraiment vous déconnecter? </br></br>
						<a href="logout.php" class="btn btn-success">Oui</a>
						<a href="profile.php" class="btn btn-danger">Non</a>
					</div>
					<form action="#" id="logout-form" method="post" name="logout-form" style="display: none;"></form>
				</div>
			</div>
		</div>
	</div>

	
	<? include 'script.php' ?>
	 
	<script defer src="assets/js/profile.js"></script>
</body>
</html>