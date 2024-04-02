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


function contenu(String $anneeSelec, int $semestreSelec)
{
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/compSem.json');

	// Conversion des données JSON en tableau associatif
	$data = json_decode($jsonData, true);
	
	$contenu = '
	<div>
		<h1>Génération du jury</h1>
		<div class="container">
			<h2>%%ETUDIANT SPECIFIQUE%%</h1>
				<table class="block">
					<thead>
						<tr>
							<th>NIP</th>
							<th>nom</th>
							<th>moy</th>
							<th>TP </th>
							<th>TD </th>';
					
	$headerCreated = false;
	foreach ($data as $annee)
	{
		if ($anne['anneLib'] == $anneeSelec)
			foreach ($anne['semesters'] as $sem)
			{
				if ($sem['semId'] == $semestreSelec)
				{
					$headerCreated = true;
					foreach ($competences as $comp)
						$contenu .= '<th>C'.comp['compid'].'</th>';
				}

				if ($headerCreated) break;
			}
	}
	
	$contenu .=			'</tr>
						</thead>
						<tbody>';

	$semesterFound = false;
	foreach ($data as $annee)
	{
		if ($anne['anneLib'] == $anneeSelec)
			foreach ($anne['semesters'] as $sem)
			{
				$semesterFound = true;
				if ($sem['semId'] == $semestreSelec)
				{
					$contenu .= '<tr>';

					foreach($sem['etd'] as $student)
					{
						$contenu .= 
						'<td>'.$student['etdId'] .'</td>'.
						'<td>'.$student['etdnom'].'</td>'.
						'<td>'.$student['moySem'].'</td>'.
						'<td>'.$student['etdgroupetp'].'</td>'.
						'<td>'.$student['etdgroupetd'].'</td>';
					}

					foreach($student['competences'] as $comp)
						$contenu .= '<td>'.$comp['moy'] .'</td>'

					$contenu .= '</tr>';
				}
			}

		if ($semesterFound) break;
	}

	$contenu .= '<div class="gridRessource">
					<label id="modification">*Modification non sauvegardée*</label>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Prévisualiser</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</button>
					<a href="generation.php"><button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button></a>
				</div>
		</div>
	</div>';

	echo $contenu;
}

head('css/commissionEtFicheAvis.css');

contenu(); // TODO on doit avoir comme info l'id du semestre et le lib de l'année

foot();
?>