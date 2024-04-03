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


// Vérifier si les champs du formulaire sont soumis
if (isset($_POST['user']) && isset($_POST['password'])) {
	$username = $_POST['user'];
	$password = $_POST['password'];

	foreach ($data as $user) {
		if ($user['userlogin'] === $username && $user['userpassword'] === $password) {
			$_SESSION['username'] = $username;
			$_SESSION['role'] = $user['userdroit'];

			if ( $user['userdroit'] == 2)
				header("Location: ./accueilAdmin.php");
			else
				header("Location: ./accueilUtilisateur.php");

			exit;
		}
	}
}

function contenu()
{
	$year = unserialize($_SESSION['year']);

	echo '
	<h1>Génération avis de poursuite d\'études '. $year['annelib'] .' </h1>
	<div class="container">

	<form method="POST" action="suiv">
		<h2>Choix des paramètres</h2>
		<div class="gridLibImport">
			<span>Nom du chef de Dept. :</span>
			<input type="text" id="chef" name="chef" value="">

			<span>Logo 1 :</span>
			<input type="file" id="logo1" name="logo1" accept="image/png, image/jpeg">

			<span>Logo 2 :</span>
			<input type="file" id="logo2" name="logo2" accept="image/png, image/jpeg">

			<span>Signature et cachet du Dept. :</span>
			<input type="file" id="sign" name="sign" accept="image/png, image/jpeg">

            </div>
            <button class="validateButtonStyle" type="import" name="signCachet" value="">Remplir les avies</button>
	
	</form>
	</div>';
}

head('css/generation.css');

contenu();

foot();





?>