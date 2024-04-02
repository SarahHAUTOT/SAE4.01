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
				<input type="text" id="anneeLib" name="année" placeholder="YYYY-YYYY + 1">
			</div>
			<h2 class="semestre">S1</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
			</div>

			<hr>

			<h2 class="semestre">S2</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
			</div>
			
			<hr>

			<h2 class="semestre">S3</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
			</div>
			
			<hr>

			<h2 class="semestre">S4</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
			</div>
			
			<hr>

			<h2 class="semestre">S5</h2>
			<div class="gridLibImport">
				<span>Fichier Moyenne:</span>
				<input type="file" class="moyenne" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

				<span>Fichier Jury:</span>
				<input type="file" class="jury" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
			</div>


			<br>
			<button class="validateButtonStyle" id="save" type="import" name="signCachet" value="">Valider</button>

			<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
			<script src="../src/app/import/import.js"></script>

		</div>';
}

head('css/generation.css');

contenue();

foot();





?>