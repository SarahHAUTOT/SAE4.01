<?php

//Pour la structure
include 'background.php';
include('../src/app/export/Export.php'); 



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

$year = $_SESSION['year'];

// Vérifier si les champs du formulaire sont soumis
if (isset($_POST['chef'])  && !empty($_POST['chef'])  &&
    isset($_POST['sign'])  && !empty($_POST['sign'])  &&
    isset($_POST['logo1']) && !empty($_POST['logo1']) &&
	isset($_POST['logo2']) && !empty($_POST['logo2'])) 
{
	ajouterInfo($_POST['logo1'], $_POST['logo2'], $_POST['chef'], $_POST['sign'], $_SESSION['annelib']);
	header('Location: squeletteFicheAvis.php');
}

function contenu()
{
    $lib = $_SESSION['annelib'];
	echo '
	<h1>Génération avis de poursuite d\'études '. $lib .' </h1>
	<div class="container">

	<form method="POST" action="generationPoursuite.php">
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