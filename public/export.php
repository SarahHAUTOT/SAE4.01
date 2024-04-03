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


function contenu()
{
	echo '
	<h1> Export </h1>
		<div class="container">
			<div class="gridLibImport">
				<span>Recherche :</span>
				<input type="text" id="inputRecherche" name="inputRecherche" placeholder="Recherche. . .">
			</div>
			<span> Filtres :</span> <br>

			<input type="radio" id="radioPE" name="typeExport" value="PE">
			<label for="radioPE">Poursuit d\'étude</label>
			<input type="radio" id="radioCO" name="typeExport" value="CO">
			<label for="radioCO">Commission</label>
			<input type="radio" id="radioJU" name="typeExport" value="JU">
			<label for="radioJU">Jury</label>

			<table id="tableauExports" class="recherche">
			</table>

		</div>
		
		<script>
		fetch(\'../data/export.json\')
			.then(response => response.json())
			.then(data => {

				// Événement pour le radio bouton "PE"
				document.getElementById(\'radioPE\').addEventListener(\'change\', () => {
					afficherExport(\'PE\', data, document.getElementById(\'inputRecherche\').value.trim());
				});

				// Événement pour le radio bouton "CO"
				document.getElementById(\'radioCO\').addEventListener(\'change\', () => {
					afficherExport(\'CO\', data, document.getElementById(\'inputRecherche\').value.trim());
				});

				// Événement pour le radio bouton "JU"
				document.getElementById(\'radioJU\').addEventListener(\'change\', () => {
					afficherExport(\'JU\', data, document.getElementById(\'inputRecherche\').value.trim());
				});

				document.getElementById(\'inputRecherche\').addEventListener(\'input\', function () {
					const termeRecherche = this.value.trim(); // Obtenir le terme de recherche sans les espaces de début et de fin
					const typeExport = document.querySelector(\'input[type="radio"]:checked\').value; // Obtenir le type d\'export sélectionné
					afficherExport(typeExport, data, termeRecherche);
				});
			})
			.catch(error => console.error(\'Erreur lors du chargement du fichier JSON :\', error));

		function afficherExport(typeExport, data, termeRecherche) {
			if (typeExport === \'PE\') {
				//Poursuite d\'étude
				// Supprimer les anciennes données
				const tableauExports = document.getElementById(\'tableauExports\');
				tableauExports.innerHTML =\'
				<thead>
					<tr>
						<th>Code NIP</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Année</th>
						<th>Téléchargement</th>
					</tr>
				</thead>
				<tbody>\';

				const exportsFiltres = data.filter(exportData => exportData.exporttype === typeExport && exportData.exportchemin.toLowerCase().includes(termeRecherche.toLowerCase()));

				// Créer les lignes du tableau
				exportsFiltres.forEach(exportData => {
					const nomFichier = exportData.exportchemin;
					const parties = nomFichier.split(\'_\');

					console.log("test", nomFichier)
					if (nomFichier.toLowerCase().includes(termeRecherche.toLowerCase())) {
						console.log("contenu", nomFichier);
					} else {
						console.log("c\'est non", nomFichier);
					}

					const newRow = tableauExports.insertRow();
					newRow.innerHTML = \'
							<td>${parties[0]}</td>
							<td>${parties[1]}</td>
							<td>${parties[2]}</td>
							<td>${parties[3].split(\'.\')[0]}</td>
							<td><a href=\"../generer/poursuite${nomFichier}.pdf\" download><button>Télécharger des données</button></a></td>\';
				});
			}
			else {
				//commission et jury
				const tableauExports = document.getElementById('tableauExports');
				tableauExports.innerHTML = \'
				<thead>
					<tr>
						<th>année</th>
						<th>semestre</th>
						<th>Téléchargement</th>
					</tr>
				</thead>
				<tbody>\';

				// Filtrer les données en fonction du type d\'export
				const exportsFiltres = data.filter(exportData => exportData.exporttype === typeExport && exportData.exportchemin.toLowerCase().includes(termeRecherche.toLowerCase()));

				// Créer les lignes du tableau
				exportsFiltres.forEach(exportData => {
					const nomFichier = exportData.exportchemin;
					const parties = nomFichier.split(\'_\');

					const newRow = tableauExports.insertRow();
					newRow.innerHTML = \'
		<td>${parties[0]}</td>
		<td>${parties[1]}</td>
		<td><a href="../generer/poursuite${nomFichier}.csv" download><button>Télécharger des données</button></a></td>\';
				});
			}

		}



	</script>';
}

head('css/generation.css');

contenu();

foot();





?>