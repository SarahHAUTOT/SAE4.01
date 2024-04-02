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
if ($_SESSION['role'] != 2 && $_SESSION['role'] != 1 ) {
    // Rediriger vers une page d'erreur si l'utilisateur n'a pas les droits nécessaires
    header('Location: accueilUtilisateur.php');
    exit;
}


function contenue()
{
	echo '
	<h1> Export </h1>
	<div class="container">
		<div class="gridLibImport">
			<span>Recherche :</span>
			<input type="text" name="recherche" value="Michel">
		</div>
		<span> Filtres :</span> <br>
		<input type="radio" id="poursuite etude" name="drone" checked/>
		<label>Poursuite d\'étude</label>
		<input type="radio" id="commission" name="drone"/>
		<label>Commission</label>
		<input type="radio" id="jury" name="drone"/>
		<label>Jury</label>

		<table class="recherche">
			<caption> recherche de michel en poursuite d\'étude</caption>
			<thead>
				<tr>
					<th>Code NIP</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Année</th>
					<th>Téléchargement</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>8420</td>
					<td>Thomas</td>
					<td>Micheline</td>
					<td>2022-2023</td>
					<td><a href="pdf/exemple.pdf" download><button>Télécharger des données</button></a></td>
				</tr>
				<tr>
					<td>8666</td>
					<td>Robert</td>
					<td>Michel</td>
					<td>2022-2023</td>
					<td><a href="pdf/exemple.pdf" download><button>Télécharger des données</button></a></td>
				</tr>
				<tr>
					<td>8842</td>
					<td>Michel</td>
					<td>Durand</td>
					<td>2022-2023</td>
					<td><a href="pdf/exemple.pdf" download><button>Télécharger des données</button></a></td>
				</tr>
				<tr>
					<td>8860</td>
					<td>Dubois</td>
					<td>Michel</td>
					<td>2022-2023</td>
					<td><a href="pdf/exemple.pdf" download><button>Télécharger des données</button></a></td>
				</tr>
				<tr>
					<td>8869</td>
					<td>Michel</td>
					<td>Paul</td>
					<td>2022-2023</td>
					<td><a href="pdf/exemple.pdf" download><button>Télécharger des données</button></a></td>
				</tr>
			</tbody>
			<tfoot>

			</tfoot>
		</table>
		
	</div>';
}

head('css/generation.css');

contenue();

foot();





?>