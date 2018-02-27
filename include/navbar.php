<!doctype html>
<html lang="fr">

<nav class="navbar navbar-light bg-faded navbar-toggleable-md fixed-top">
	<button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="#">VARITIS</a>

	<div class="navbar-collapse collapse" id="navbarsExampleDefault" aria-expanded="false" style="">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item home">
				<a class="nav-link" href="index.php"><i class="fas fa-home fa-2x"></i><span id="nav-icon-text">Accueil</span></a>
			</li>
			<li class="nav-item sell">
				<a class="nav-link" href="sell.php"><i class="fas fa-shopping-cart fa-2x"></i><span id="nav-icon-text">Vendre</span></a>
			</li>
			<li class="nav-item buy">
				<a class="nav-link" href="buy.php"><i class="fas fa-cart-plus fa-2x" ></i><span id="nav-icon-text">Acheter</span></a>
			</li>
		</ul>
		
		<div class="col-md-2"></div>
		<!-- Si déconnecté : Bouton de connexion/inscription -->
		<?php if(empty($_SESSION['id_me'])){?>
		<ul class="navbar-nav mr-sm-2">
			<li class="nav-item search">
				<a class="nav-link" href="search.php"><i class="fas fa-search fa-2x" id="nav-icon"></i></a>
			</li>
			<li class="nav-item login">
				<div class="input-group">
					<a class="nav-link login" href="login.php"><i class="far fa-user fa-2x" id="nav-icon"></i>Mon compte</a>
					<!--<button class="btn btn-outline-success" type="button" onclick="location.href='login.php';"><i class="fa fa-user-o" ></i> Espace membre</button>-->
				</div>
			</li>
		</ul>
		<!-- Sinon : Bouton Mon compte -->
		<?php } else { 
			$membre = new Membre();
		?>
		<ul class="navbar-nav mr-sm-2">
			<li class="nav-item search" id="nav-icon">
				<a class="nav-link" href="search.php"><i class="fas fa-search fa-2x"> </i></a>
			</li>
			<div class="dropdown">
				<li class="nav-item notif" id="nav-icon dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="fa-layers fa-2x">
						<a class="nav-link" href="notif.php">
							<i class="far fa-bell" > </i>
							<span id="notif-count" style="background:Tomato"></span>
						</a>
					</span>
				</li>
				<!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				</div> -->
				<div id="notificationContainer">
					<div id="notificationTitle">Notifications
						<div class="notif-lu"><span class="fas fa-eye" data-toggle="tooltip" data-placement="bottom" title="Marquer tout comme lu"></span></div>
					</div>
					<div id="notificationsBody" class="notifications"></div>
					<div id="notificationFooter"><a href="#">Afficher plus</a></div>
				</div>
			</div>
			<li class="nav-item account" id="nav-icon">
				<div class="input-group">
					<a class="nav-link" href="profile.php"><i class="far fa-address-card fa-2x"></i><span id="nav-icon-text"><?php echo $membre->getUsername($_SESSION['id_me']); ?></span></a>
					<!--<button class="btn btn-outline-success" type="button" onclick="location.href='profile.php';"><i class="fa fa-address-card" ></i></i> Mon compte</button>-->
				</div>
			</li>
		</ul>
		<?php } ?>
	</div>
</nav>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</html>