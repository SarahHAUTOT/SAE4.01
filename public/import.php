<?php

//Pour la structure
include 'background.php';



// Démarrer la session
session_start();

// Vérifier si la session est ouverte
if (!isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion si la session n'est pas ouverte
    header('Location: connexion.php');
    exit;
}

// Vérifier les droits de l'utilisateur
if ($_SESSION['role'] != 2) {
    // Rediriger vers une page d'erreur si l'utilisateur n'a pas les droits nécessaires
    header('Location: accueilUtilisateur.php');
    exit;
}


function contenue()
{
	echo '
		<h1>Importation des données</h1>
		<div class="container">
			<div class="gridLibImport">
				<span>Libellé année</span>
				<input type="text" id="année" name="année" value="">
			</div>
			<h2 class="semestre">S1</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept="image/png, image/jpeg">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept="image/png, image/jpeg">
			</div>

			<hr>

			<h2 class="semestre">S2</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept="image/png, image/jpeg">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept="image/png, image/jpeg">
			</div>
			
			<hr>

			<h2 class="semestre">S3</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept="image/png, image/jpeg">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept="image/png, image/jpeg">
			</div>
			
			<hr>

			<h2 class="semestre">S4</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept="image/png, image/jpeg">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept="image/png, image/jpeg">
			</div>
			
			<hr>

			<h2 class="semestre">S5</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept="image/png, image/jpeg">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept="image/png, image/jpeg">
			</div>


			<br>
			<a href="squeletteFicheAvis.html"><button class="validateButtonStyle" type="import" name="signCachet" value="">Valider</button></a>
		</div>';
}

head('css/generation.css');

contenue();

foot();





?>