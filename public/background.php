<?php

function head($css, $username=""){echo'
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>DocSco</title>
	<link rel="stylesheet" href="css/background.css">
	<link rel="stylesheet" href="'. $css .'">
</head>

<body>

	<header>
	<a href="accueilAdmin.php"><div class="logo"> 
			<img src="images/logo.png" alt="Logo DocSco">
			<h1>DocSco</h1>
		</div></a>
		<div class="user">
			'. $username .'
		</div>
	</header>

	<main>';
}

function foot(){echo'
	</main>

	<footer>
		<div class="logo">
			<a href="https://eureka.univ-lehavre.fr/"> <img src="images/eureka.svg" alt="Eureka"> </a>
			<a href="https://diw.iut.univ-lehavre.fr/pedago/index.xml"> <img src="images/departement.gif" alt="Departement informatique"></a>
		</div>
	</footer>

</body>

</html>';}

?>