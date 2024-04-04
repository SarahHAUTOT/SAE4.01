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

// Vérifier si on a appuyer sur le btn suivant
if (isset($_POST['idStudent'])) {
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/pe.json');
	$students = json_decode($jsonData, true);

	contenu($students[ $_POST['idStudent'] ]);
}
else
{
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/pe.json');
	$students = json_decode($jsonData, true);
	contenu($students[0]);
}


function contenu($etd)
{
	echo '
	<div>
	<h1>Génération avis de poursuite d\'études</h1>
	<div class="container">
		<h2 id="etudiant">
			</h1>
			<table>
				<tbody>
					<tr>
						<th>Apprentissage</th>
						<td>BUT 1</td>
						<td><input id="app1" type="checkbox" checked="non" /></td>
						<td>BUT 2</td>
						<td><input id="app2" type="checkbox" checked="non" /></td>
						<td>BUT 3</td>
						<td><input id="app3" type="checkbox" checked="non" /></td>
					</tr>
					<tr>
						<th>Parcours d’études</th>
						<td>n-2</td>
						<td><input type="number" id="pde1" min="0"></td>
						<td>n-1</td>
						<td><input type="number" id="pde2" min="0"></td>
						<td>n</td>
						<td><input type="number" id="pde3" min="0"></td>
					</tr>
					<tr>
						<th>Parcours BUT</th>
						<td colspan="6">A "Réalisation d\'application: conception...</td>
					</tr>
					<tr>
						<th>Si mobilité à létranger (lieu,durée)</th>
						<td contenteditable="true" colspan="6"><textarea id="mob"></textarea></td>
					</tr>
				</tbody>
			</table>
			<hr>


			<h3>Résultat des compétences</h3>
			<table>
				<thead>
					<tr>
						<th rowspan="2"></th>
						<th colspan="2">BUT 1</th>
						<th colspan="2">BUT 2</th>
					</tr>
					<tr>
						<th>Rang</th>
						<th>Moy.</th>
						<th>Rang</th>
						<th>Moy.</th>
					</tr>
				</thead>
				<tbody id="BUT12">
					<tr>
						<th>Nombre d\'absences injustifiées</th>
						<td colspan="2"><input type="number" id="abs1" min="0"></td>
						<td colspan="2"><input type="number" id="abs2" min="0"></td>
					</tr>
				</tbody>
			</table>
			<table>
				<thead>
					<tr>
						<th rowspan="2"></th>
						<th colspan="2">BUT 3</th>
					</tr>
					<tr>
						<th>Rang</th>
						<th>Moy.</th>
					</tr>
				</thead>
				<tbody id="BUT3">
					<tr>
						<th>Nombre d\'absences injustifiées</th>
						<td colspan="2"><input type="number" id="abs3" min="0"></td>
					</tr>
				</tbody>
			</table>
			<hr>
			<h3>Avis de l’équipe pédagogique pour la poursuite d’études après le BUT 3</h3>
			<table>
				<thead>
					<tr>
						<th colspan="2"></th>
						<th>Très Favorable</th>
						<th>Favorable</th>
						<th>Assez Favorable</th>
						<th>Sans Avis</th>
						<th>Réservé</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th rowspan="2">Pour l\'étudiant</th>
						<th>En école d\'ingénieurs</th>
						<td><input type="radio" id="5ingé" name="avisInge" checked></td>
						<td><input type="radio" id="4ingé" name="avisInge"></td>
						<td><input type="radio" id="3ingé" name="avisInge"></td>
						<td><input type="radio" id="2ingé" name="avisInge"></td>
						<td><input type="radio" id="1ingé" name="avisInge"></td>
					</tr>
					<tr>
						<th>En Master</th>
						<td><input type="radio" id="1mast" name="avisMaster" checked></td>
						<td><input type="radio" id="2mast" name="avisMaster"></td>
						<td><input type="radio" id="3mast" name="avisMaster"></td>
						<td><input type="radio" id="4mast" name="avisMaster"></td>
						<td><input type="radio" id="5mast" name="avisMaster"></td>
					</tr>
					<tr>
						<th>Commentaire</th>
						<td colspan="6"><textarea id="comm"></textarea></td>
					</tr>
				</tbody>
			</table>
			<div class="gridRessource">
				<button id="suivant" class="validateButtonStyle" type="import" name="Suivant"
					value="">Suivant</button>
			</div>
	</div>
</div>

<script>
	const avi = {};
	// Charger le fichier JSON
	fetch(\'../data/etudiants.json\')
		.then(response => response.json())
		.then(data => {

			// Obtenir les informations de l\'étudiant
			const etudiant = data[0];

			//prenom et nom de l\'étudiant
			const etud = document.getElementById(\'etudiant\');
			etud.innerHTML = `${etudiant.etdprenom} ${etudiant.etdnom}`;
			avi.nom = etudiant.etdnom;
			avi.prenom = etudiant.etdprenom;

			avi.annee = [
				{ "C1": {}, "C2": {}, "C3": {}, "C4": {}, "C5": {}, "C6": {}, "Maths": {}, "Anglais": {} },
				{ "C1": {}, "C2": {}, "C3": {}, "C4": {}, "C5": {}, "C6": {}, "Maths": {}, "Anglais": {} },
				{ "C1": {}, "C2": {}, "C6": {}, "Maths": {}, "Anglais": {} }];

			//BUT 1 et 2
			const but12 = document.getElementById(\'BUT12\');

			const but121 = but12.insertRow(0);
			but121.innerHTML = `
				<th>UE1 – Réaliser des applications</th>
				<td>${etudiant.but1[0].rang}</td>
				<td>${etudiant.but1[0].moy}</td>
				<td>${etudiant.but2[0].rang}</td>
				<td>${etudiant.but2[0].moy}</td>`;

			avi.annee[0].C1.rang = etudiant.but1[0].rang;
			avi.annee[0].C1.moy = etudiant.but1[0].moy;
			avi.annee[1].C1.rang = etudiant.but2[0].rang;
			avi.annee[1].C1.moy = etudiant.but2[0].moy;

			const but122 = but12.insertRow(1);
			but122.innerHTML = `
				<th>UE2 – Optimiser des applicationsns</th>
				<td>${etudiant.but1[1].rang}</td>
				<td>${etudiant.but1[1].moy}</td>
				<td>${etudiant.but2[1].rang}</td>
				<td>${etudiant.but2[1].moy}</td>`;

			avi.annee[0].C2.rang = etudiant.but1[1].rang;
			avi.annee[0].C2.moy = etudiant.but1[1].moy;
			avi.annee[1].C2.rang = etudiant.but2[1].rang;
			avi.annee[1].C2.moy = etudiant.but2[1].moy;

			const but123 = but12.insertRow(2);
			but123.innerHTML = `
				<th>UE3 – Administrer des systèmes</th>
				<td>${etudiant.but1[2].rang}</td>
				<td>${etudiant.but1[2].moy}</td>
				<td>${etudiant.but2[2].rang}</td>
				<td>${etudiant.but2[2].moy}</td>`;

			avi.annee[0].C3.rang = etudiant.but1[2].rang;
			avi.annee[0].C3.moy = etudiant.but1[2].moy;
			avi.annee[1].C3.rang = etudiant.but2[2].rang;
			avi.annee[1].C3.moy = etudiant.but2[2].moy;

			const but124 = but12.insertRow(3);
			but124.innerHTML = `
				<th>UE4 – Gérer des données</th>
				<td>${etudiant.but1[3].rang}</td>
				<td>${etudiant.but1[3].moy}</td>
				<td>${etudiant.but2[3].rang}</td>
				<td>${etudiant.but2[3].moy}</td>`;

			avi.annee[0].C4.rang = etudiant.but1[3].rang;
			avi.annee[0].C4.moy = etudiant.but1[3].moy;
			avi.annee[1].C4.rang = etudiant.but2[3].rang;
			avi.annee[1].C4.moy = etudiant.but2[3].moy;

			const but125 = but12.insertRow(4);
			but125.innerHTML = `
				<th>UE5 – Conduire des projets</th>
				<td>${etudiant.but1[4].rang}</td>
				<td>${etudiant.but1[4].moy}</td>
				<td>${etudiant.but2[4].rang}</td>
				<td>${etudiant.but2[4].moy}</td>`;

			avi.annee[0].C5.rang = etudiant.but1[4].rang;
			avi.annee[0].C5.moy = etudiant.but1[4].moy;
			avi.annee[1].C5.rang = etudiant.but2[4].rang;
			avi.annee[1].C5.moy = etudiant.but2[4].moy;

			const but126 = but12.insertRow(5);
			but126.innerHTML = `
				<th>UE6 – Collaborer</th>
				<td>${etudiant.but1[5].rang}</td>
				<td>${etudiant.but1[5].moy}</td>
				<td>${etudiant.but2[5].rang}</td>
				<td>${etudiant.but2[5].moy}</td>`;

			avi.annee[0].C6.rang = etudiant.but1[5].rang;
			avi.annee[0].C6.moy = etudiant.but1[5].moy;
			avi.annee[1].C6.rang = etudiant.but2[5].rang;
			avi.annee[1].C6.moy = etudiant.but2[5].moy;

			const but12math = but12.insertRow(6);
			but12math.innerHTML = `
				<th>Maths</th>
				<td>${etudiant.but1[6].rang}</td>
				<td>${etudiant.but1[6].moy}</td>
				<td>${etudiant.but2[6].rang}</td>
				<td>${etudiant.but2[6].moy}</td>`;

			avi.annee[0].Maths.rang = etudiant.but1[6].rang;
			avi.annee[0].Maths.moy = etudiant.but1[6].moy;
			avi.annee[1].Maths.rang = etudiant.but2[6].rang;
			avi.annee[1].Maths.moy = etudiant.but2[6].moy;

			const but12anglais = but12.insertRow(7);
			but12anglais.innerHTML = `
				<th>Anglais</th>
				<td>${etudiant.but1[7].rang}</td>
				<td>${etudiant.but1[7].moy}</td>
				<td>${etudiant.but2[7].rang}</td>
				<td>${etudiant.but2[7].moy}</td>`;

			avi.annee[0].Anglais.rang = etudiant.but1[7].rang;
			avi.annee[0].Anglais.moy = etudiant.but1[7].moy;
			avi.annee[1].Anglais.rang = etudiant.but2[7].rang;
			avi.annee[1].Anglais.moy = etudiant.but2[7].moy;

			//BUT 3
			const but3 = document.getElementById(\'BUT3\');

			const but31 = but3.insertRow(0);
			but31.innerHTML = `
				<th>UE1 – Réaliser des applications</th>
				<td>${etudiant.but3[0].rang}</td>
				<td>${etudiant.but3[0].moy}</td>`;

			avi.annee[2].C1.rang = etudiant.but3[0].rang;
			avi.annee[2].C1.moy = etudiant.but3[0].moy;

			const but32 = but3.insertRow(1);
			but32.innerHTML = `
				<th>UE2 – Optimiser des applicationsns</th>
				<td>${etudiant.but3[1].rang}</td>
				<td>${etudiant.but3[1].moy}</td>`;

			avi.annee[2].C2.rang = etudiant.but3[1].rang;
			avi.annee[2].C2.moy = etudiant.but3[1].moy;

			const but36 = but3.insertRow(2);
			but36.innerHTML = `
				<th>UE6 – Collaborer</th>
				<td>${etudiant.but3[2].rang}</td>
				<td>${etudiant.but3[2].moy}</td>`;

			avi.annee[2].C6.rang = etudiant.but3[2].rang;
			avi.annee[2].C6.moy = etudiant.but3[2].moy;

			const but3math = but3.insertRow(3);
			but3math.innerHTML = `
				<th>Maths</th>
				<td>${etudiant.but3[3].rang}</td>
				<td>${etudiant.but3[3].moy}</td>`;

			avi.annee[2].Maths.rang = etudiant.but3[3].rang;
			avi.annee[2].Maths.moy = etudiant.but3[3].moy;

			const but3anglais = but3.insertRow(4);
			but3anglais.innerHTML = `
				<th>Anglais</th>
				<td>${etudiant.but3[4].rang}</td>
				<td>${etudiant.but3[4].moy}</td>`;

			avi.annee[2].Anglais.rang = etudiant.but3[4].rang;
			avi.annee[2].Anglais.moy = etudiant.but3[4].moy;
		})
		.catch(error => console.error(\'Erreur lors du chargement du fichier JSON :\', error));

	//commentaire
	const commentaire = document.getElementById(\'comm\');
	avi.comm = commentaire.value;
	commentaire.addEventListener(\'input\', function () {
		avi.comm = commentaire.value;
	});

	//mobilité
	const mobil = document.getElementById(\'mob\');
	avi.mobilite = mobil.value;
	mobil.addEventListener(\'input\', function () {
		avi.mobilite = mobil.value;
	});

	//apprentissage
	avi.apprenti = ["non", "non", "non"];

	const apprenti1 = document.getElementById(\'app1\');
	apprenti1.addEventListener(\'change\', function () {
		if (apprenti1.checked) {
			avi.apprenti[0] = "oui";
		} else {
			avi.apprenti[0] = "non";
		}
	})

	const apprenti2 = document.getElementById(\'app2\');
	apprenti2.addEventListener(\'change\', function () {
		if (apprenti2.checked) {
			avi.apprenti[1] = "oui";
		} else {
			avi.apprenti[1] = "non";
		}
	})

	const apprenti3 = document.getElementById(\'app3\');
	apprenti3.addEventListener(\'change\', function () {
		if (apprenti3.checked) {
			avi.apprenti[2] = "oui";
		} else {
			avi.apprenti[2] = "non";
		}
	})

	// avis ingenieur
	avi.avisIngenieur = 5;

	const ingenieurs = document.querySelectorAll(\'input[type="radio"][name="avisInge"]\');
	ingenieurs.forEach(ingenieur => {
		ingenieur.addEventListener(\'change\', function () {
			// Vérifier quel bouton radio est sélectionné
			if (this.checked) {
				switch (this.id) {
					case "1ingé":
						avi.avisIngenieur = 1;
						break;
					case "2ingé":
						avi.avisIngenieur = 2;
						break;
					case "3ingé":
						avi.avisIngenieur = 3;
						break;
					case "4ingé":
						avi.avisIngenieur = 4;
						break;
					case "5ingé":
						avi.avisIngenieur = 5;
						break;
					default:
						break;
				};
			}
		});
	});


	// avis ingenieur
	avi.avisMaster = 5;

	const masters = document.querySelectorAll(\'input[type="radio"][name="avisMaster"]\');
	masters.forEach(master => {
		master.addEventListener(\'change\', function () {
			// Vérifier quel bouton radio est sélectionné
			if (this.checked) {
				switch (this.id) {
					case "1mast":
						avi.avisMaster = 1;
						break;
					case "2mast":
						avi.avisMaster = 2;
						break;
					case "3mast":
						avi.avisMaster = 3;
						break;
					case "4mast":
						avi.avisMaster = 4;
						break;
					case "5mast":
						avi.avisMaster = 5;
						break;
					default:
						break;
				};
			}
		});
	});

	const suivant = document.getElementById(\'suivant\');
	suivant.addEventListener(\'click\', function () {
		callPHP("../src/app/export/export.php","avi etudiant", avi);
	});


	function callPHP(file, action, datas) {
		return new Promise((resolve, reject) => {
			fetch(file, {
				method: \'POST\',
				headers: {
					\'Content-Type\': \'application/json\',
				},
				body: JSON.stringify({ action: action, datas }),
			})
				.then(response => {
					if (!response.ok) {
						throw new Error(\'Network response was not ok\');
					}
					return response.text();
				})
				.then(data => {
					resolve(data); // Renvoie le message de succès du script PHP
				})
				.catch(error => {
					reject(error);
				});
		});
	}

</script>

<script>
	const abs1 = document.getElementById(\'abs1\');
	abs1.addEventListener(\'input\', function () {
		let valeur = abs1.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs1.value = valeur;
	});

	const abs2 = document.getElementById(\'abs2\');
	abs2.addEventListener(\'input\', function () {
		let valeur = abs2.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs2.value = valeur;
	});

	const abs3 = document.getElementById(\'abs3\');
	abs3.addEventListener(\'input\', function () {
		let valeur = abs3.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs3.value = valeur;
	});


	const pde1 = document.getElementById(\'pde1\');
	pde1.addEventListener(\'input\', function () {
		let valeur = pde1.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		pde1.value = valeur;
	});

	const pde2 = document.getElementById(\'pde2\');
	pde2.addEventListener(\'input\', function () {
		let valeur = pde2.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		pde2.value = valeur;
	});

	const pde3 = document.getElementById(\'pde3\');
	pde3.addEventListener(\'input\', function () {
		let valeur = pde3.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs3.value = valeur;
	});
</script>
';
}

head('css/commissionEtFicheAvis.css');

contenu();

foot();





?>