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

if (isset($_POST['action'])) {
    $action = $_POST['action'];

	echo ("hey");

    switch ($action) {
        case 'pe':
			//générationd des poursuite d'études
			header("Location: connexion.php");
            break;
        case 'jury':
			//Génération jury
            break;
        case 'comm':
			//Génération de commissions
            break;
        default:
            echo "Action non reconnue";
            break;
    }
}


function contenue()
{
	$jsonData = file_get_contents('../data/donnees.json');
	$data = json_decode($jsonData, true);



	echo '
	<h1> Génération </h1>
	<div class="container">
	<form action="generation.php" method="post">
		<div class="generationSection">
			<h2>Avis de poursuite d\'études</h2>
			<div class="gridLibImport">

				<span>Choix Année</span>
				<select>';
				
	foreach ($data as $anneeData) 
		if (count($anneeData['semesters'][4]['etd']) > 0)
				echo '
					<option value="annee4">'. $anneeData['annelib'] .'</option>';


				echo '</select>
			</div>

			<button  type="submit" name="action" value="pe" class="validateButtonStyle">Continuer vers export d\'avis de poursuite d\'étude</button>
		</div>
		<div class="generationSection">
			<h2>Préparation aux commissions/jurys</h2>
			<div class="gridLibImport" >
				<span>Choix Année</span>
				<select>';

				
	
				
	foreach ($data as $anneeData) 
		echo'	<option value="annee4">'. $anneeData['annelib'] .'</option>';


	echo'
				</select>

				<span>Choix semestre</span>
				<select>
					<option value="semestre1">S1</option>
					<option value="semestre2">S2</option>
					<option value="semestre3">S3</option>
					<option value="semestre4">S4</option>
					<option value="semestre4">S5</option>
				</select>
			</div>
			
			<button type="submit" name="action" value="jury" class="validateButtonStyle">Générer Jury</button>
			<button type="submit" name="action" value="com" class="validateButtonStyle">Générer Commission</button>
		</div>
		</form>
	</div>';
}

head('css/generation.css');

contenue();

foot();
?>