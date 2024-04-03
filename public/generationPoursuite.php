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
	<h1>Génération avis de poursuite d\'études</h1>
	<div class="container">
		<h2>Choix des paramètres</h2>
		<div class="gridLibImport">
			<span>Nom du chef de Dept. :</span>
			<input type="text" id="nom-chef-dept" name="nom-chef-dept" value="">

			<span>Logo 1 :</span>
			<input type="file" id="logo1" name="logo1" accept="image/png, image/jpeg">

			<span>Logo 2 :</span>
			<input type="file" id="logo2" name="logo2" accept="image/png, image/jpeg">

			<span>Signature et cachet du Dept. :</span>
			<input type="file" id="sign-cach-dept" name="sign-cach-dept" accept="image/png, image/jpeg">

            </div>
            <a href="squeletteFicheAvis.php"><button class="validateButtonStyle" type="import" name="signCachet" value="">Remplir les avies</button></a>
	</div>';
}

head('css/generation.css');

contenue();

foot();





?>