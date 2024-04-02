<?php

//Pour la structure
include 'background.php';



// Démarrer la session
session_start();

// Vérifier si la session est ouverte
if (!isset($_SESSION['role'])) {
    header('Location: connexion.php');
    exit;
}

// Vérifier les droits de l'utilisateur
if ($_SESSION['role'] != 2) {
    header('Location: accueilUtilisateur.php');
    exit;
}


function contenue()
{
	echo '
		<h1>Bienvenue Admin</h1>
		<div class="container">
			<h2>Petite description:</h2>
			<p>L\'application DocSco offre une solution essentielle pour simplifier et optimiser l\'évaluation des
				étudiants dans les départements universitaires. En centralisant les données des promotions et en
				facilitant la collaboration entre les membres du jury, DocSco vise à améliorer l\'efficacité et la
				qualité du processus d\'évaluation. Son potentiel d\'extension à d\'autres départements suggère une
				évolution continue et une pertinence à long terme dans le domaine de l\'éducation.</p>
				<span class="urgent">*ATTENTION, IL VOUS FAUDRA IMPORTER AU MINIMUM 3 ANNEES POUR POUVOIR UTILISER CETTE APPLICATION CORRECTEMENT*</span>
				<br>
				<a href=""><button class="validateButtonStyle"><img src="images/importer.png" alt="Importer"></i></button></a>
				<a href="export.html"><button class="validateButtonStyle"><img src="images/exporter.png" alt="Exporter"></button></a>
				<a href="generation.html"><button class="validateButtonStyle"><img src="images/generer.png" alt="Générer"></button></a>
		</div>';
}

head('css/accueilEtConnexion.css');

contenue();

foot();





?>




	