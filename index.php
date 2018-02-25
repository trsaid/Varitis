<?php
session_start();

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="assets/icon.ico" />
	
	<title>Varitis - Acceuil</title>
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	
	
	<link rel="stylesheet" href="assets/css/style.css" />
	<!-- Navbar -->
	<div class="Navbar">
		<?php include('include/navbar.php'); ?>
	</div>
</head>
<body class="home">

	<!-- barre de recherche -->
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="custom-search-input">
					<div class="input-group col-md-13">
						<input type="text" class="form-control input-lg" placeholder="Entrez un mot clé..." />
						<span class="input-group-btn">
							<button class="btn btn-info btn-lg" type="button">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<? include 'script.php' ?>
</body>

</html>