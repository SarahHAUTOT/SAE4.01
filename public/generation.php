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
	<h1> Génération </h1>
	<div class="container">
		<div class="generationSection">
			<h2>Avis de poursuite d\'études</h2>
			<div class="gridLibImport">
				<span>Choix Année</span>
				<select>
					<option value="annee1">Année 1</option>
					<option value="annee2">Année 2</option>
					<option value="annee3">Année 3</option>
					<option value="annee4">Année 4</option>
				</select>

				<span>Choix d\'étudiant</span>
				<select>
					<option value="etudiant1">Étudiant 1</option>
					<option value="etudiant2">Étudiant 2</option>
					<option value="etudiant3">Étudiant 3</option>
					<option value="etudiant4">Étudiant 4</option>
				</select>
			</div>

			<a href="generationPoursuite.php"><button class="validateButton">Continuer vers export d\'avis de poursuite d\'étude</button></a>
		</div>
		<div class="generationSection">
			<h2>Préparation aux commissions/jurys</h2>
			<div class="gridLibImport" >
				<span>Choix Année</span>
				<select>
					<option value="annee1">Année 1</option>
					<option value="annee2">Année 2</option>
					<option value="annee3">Année 3</option>
					<option value="annee4">Année 4</option>
				</select>

				<span>Choix semestre</span>
				<select>
					<option value="semestre1">Semestre 1</option>
					<option value="semestre2">Semestre 2</option>
					<option value="semestre3">Semestre 3</option>
					<option value="semestre4">Semestre 4</option>
				</select>
			</div>
			
			<a href=""><button class="validateButton">Générer Jury</button></a>
			<a href="commission.php"><button class="validateButton">Générer Commission</button></a>
		</div>
	</div>';
}

head('css/generation.css');

contenue();

foot();





?>