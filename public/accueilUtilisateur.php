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
if ($_SESSION['role'] != 1) {
    // Rediriger vers une page d'erreur si l'utilisateur n'a pas les droits nécessaires
    header('Location: accueilAdmin.php');
    exit;
}


function contenu()
{
	echo '
		<h1>Bienvenue Utilisateur</h1>
		<div class="container">
			<h2>Petite description:</h2>
			<p>L\'application DocSco offre une solution essentielle pour simplifier et optimiser l\'évaluation des
				étudiants dans les départements universitaires. En centralisant les données des promotions et en
				facilitant la collaboration entre les membres du jury, DocSco vise à améliorer l\'efficacité et la
				qualité du processus d\'évaluation. Son potentiel d\'extension à d\'autres départements suggère une
				évolution continue et une pertinence à long terme dans le domaine de l\'éducation.</p>
			<a href="export.php"><button class="validateButtonStyle">Exporter</button></a>
		</div>';
}

head('css/accueilEtConnexion.css');

contenu();

foot();





?>