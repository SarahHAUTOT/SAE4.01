<?php
require ('../src/app/DB.inc.php');
require ('../src/app/DBtoJSON.php');
require ('../lib/fpdf/fpdf.php');


echo "test";

function generatePDF($avi, $anneelib, $logo1, $logo2, $chef, $sign, $nbAviIng, $nbAviMast)
{
	// Inclure le fichier fpdf.php

	$pdf = new FPDF();
	$pdf->AddPage(); // Adds page to document

	/*                */
	/*  First  Line   */
	/*                */

	// Logo 1
	$pdf->Image($logo1, 10, 15, 50); 
	// Logo 2
	$pdf->Image($logo2, 160, 10, 23); 
	// Title
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetY(40);
	$pdf->Cell(0, -1, utf8_decode("Fiche d'avis poursuite d'études - Promotion " . $anneelib), 0, 1, 'C');
	$pdf->SetY(45);
	$pdf->Cell(0, -1, utf8_decode("Département Informatique IUT Le Havre"), 0, 1, 'C');




	/*             */
	/* First Table */
	/*             */

	// Title
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetY(50);
	$pdf->Cell(0, 10, utf8_decode("FICHE D'INFORMATION ETUDIANT(E)"), 0, 1, 'L');
	$pdf->Line(10, 57, 190, 57);

	$pdf->SetFont('Arial', '', 8);

	// Line 1
	$pdf->Cell( 60, 4, utf8_decode( $avi['nom'] . " - " . $avi['prenom'] . " :"), 1);
	$pdf->Cell(120, 4, '', 1);
	$pdf->Ln();

	// Line 2
	$pdf->Cell(60, 4, utf8_decode("Apprentissage : (oui/non)"), 1);
	$pdf->Cell(10, 4, 'BUT1', 1);
	$pdf->Cell(30, 4, $avi['apprenti'][0], 1);
	$pdf->Cell(10, 4, 'BUT2', 1);
	$pdf->Cell(30, 4, $avi['apprenti'][1], 1);
	$pdf->Cell(10, 4, 'BUT3', 1);
	$pdf->Cell(30, 4, $avi['apprenti'][2], 1);
	$pdf->Ln();

	// Line 3
	$pdf->Cell(60, 4, utf8_decode("Parcours d'étude"), 1);
	$pdf->Cell(10, 4, 'n-2', 1);
	$pdf->Cell(30, 4, '', 1);
	$pdf->Cell(10, 4, 'n-1', 1);
	$pdf->Cell(30, 4, '', 1);
	$pdf->Cell(10, 4, 'n', 1);
	$pdf->Cell(30, 4, '', 1);
	$pdf->Ln();

	// Line 1
	$pdf->Cell(60 , 4, utf8_decode("Parcours BUT"), 1);
	$pdf->Cell(120, 4, utf8_decode("A \"Réalisation d'applications : conception, développement, validation\""), 1);
	$pdf->Ln();

	// Line 1
	$pdf->Cell(60 , 4, utf8_decode("Si mobilité à l'étranger (lieu,durée)"), 1);
	$pdf->Cell(120, 4, utf8_decode($avi['mobilite']), 1);
	$pdf->Ln();



	/*              */
	/* Second Table */
	/*              */

	// Titre
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetY(85);
	$pdf->Cell(0, 10, utf8_decode("RESULTATS DES COMPETENCES"), 0, 1, 'L');
	$pdf->Line(10, 92, 190, 92);

	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetFillColor(220, 220, 220);

	// Line 1
	$pdf->Cell(65, 4, '', 0);
	$pdf->Cell(30, 4, 'BUT1', 1, 0, 'C', true);
	$pdf->Cell(30, 4, 'BUT2', 1, 0, 'C', true);
	$pdf->Ln();
	$pdf->SetFont('Arial', '', 8);

	// Line 2
	$pdf->Cell(65, 4, ''    , 0);
	$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
	$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
	$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
	$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
	$pdf->Ln();

	// Line 3
	$pdf->Cell(65, 4, utf8_decode('UE1 - Réaliser des applications')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C1']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C1']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C1']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C1']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 4
	$pdf->Cell(65, 4, utf8_decode('UE2 - Optimiser des applications')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C2']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C2']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C2']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C2']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 4
	$pdf->Cell(65, 4, utf8_decode('UE3 - Administrer des systèmes')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C3']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C3']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C3']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C3']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 6
	$pdf->Cell(65, 4, utf8_decode('UE4 - Gérer desdonnées')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C4']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C4']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C4']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C4']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 7
	$pdf->Cell(65, 4, utf8_decode('UE5 - Conduire des projets')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C5']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C5']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C5']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C5']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 8
	$pdf->Cell(65, 4, utf8_decode('UE6 - Collaborer')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['C6']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['C6']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C6']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['C6']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 9
	$pdf->Cell(65, 4, utf8_decode('Maths')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['Maths']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['Maths']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['Maths']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['Maths']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 10
	$pdf->Cell(65, 4, utf8_decode('Anglais')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['Anglais']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][0]['Anglais']['rang'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['Anglais']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['Anglais']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 11
	$pdf->Cell(65, 4, utf8_decode('Nombres d\'absences injustifiés')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][0]['ABS'], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][1]['ABS'], 1, 0, 'R');
	$pdf->Ln();



	/*             */
	/* Thrid Table */
	/*             */
	$pdf->Ln();

	// Line 1
	$pdf->Cell(65, 4, '', 0);
	$pdf->Cell(30, 4, 'BUT3', 1, 0, 'C', true);
	$pdf->Ln();
	$pdf->SetFont('Arial', '', 8);

	// Line 2
	$pdf->Cell(65, 4, ''    , 0);
	$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
	$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
	$pdf->Ln();

	// Line 3
	$pdf->Cell(65, 4, utf8_decode('UE1 - Réaliser des applications')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][2]['C1']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][2]['C1']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 4
	$pdf->Cell(65, 4, utf8_decode('UE2 - Optimiser des applications')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][2]['C2']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][2]['C2']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 5
	$pdf->Cell(65, 4, utf8_decode('UE6 - Collaborer')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][2]['C6']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][2]['C6']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 6
	$pdf->Cell(65, 4, utf8_decode('Maths')    , 1);
	$pdf->Cell(15, 4, $avi['annee'][2]['Maths']['moy' ], 1, 0, 'R');
	$pdf->Cell(15, 4, $avi['annee'][2]['Maths']['rang'], 1, 0, 'R');
	$pdf->Ln();

	// Line 7
	$pdf->Cell(65, 4, utf8_decode('Nombres d\'absences injustifiés')    , 1);
	$pdf->Cell(30, 4, $avi['annee'][2]['ABS'], 1, 0, 'R');
	$pdf->Ln();



	/*            */
	/* Last Table */
	/*            */

	// Titre
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetY(175);
	$pdf->Cell(0, 10, utf8_decode("Avis de l'équipe pédagogique pour la poursuite d'étude après le BUT3"), 0, 1, 'C');
	$pdf->Line(10, 182, 190, 182);

	$pdf->SetFont('Arial', '', 8);

	// Line 1
	$pdf->Cell(25, 6, '', 1, 0, 'C');
	$pdf->Cell(25, 6, '', 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Très Favorable'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Favorable'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Assez Favorable'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Sans avis'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Réservé'), 1, 0, 'C');
	$pdf->Ln();

	// Line 2
	$pdf->Cell(25, 12,utf8_decode('Pour l\'étudiant'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Ecole d\'ingénieurs'), 1, 0, 'C');

	$num = $avi['avisIngenieur'];
	for ($i = 1; $i <= 5; $i++)
		if ($i == $num)
			$pdf->Cell(25, 6, utf8_decode('X'), 1, 0, 'C');
		else
			$pdf->Cell(25, 6, utf8_decode(' '), 1, 0, 'C');

	$pdf->Ln();

	// Line 2bis
	$pdf->Cell(25, 6);
	$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');

	$num = $avi['avisMaster'];
	for ($i = 1; $i <= 5; $i++)
		if ($i == $num)
			$pdf->Cell(25, 6, utf8_decode('X'), 1, 0, 'C');
		else
			$pdf->Cell(25, 6, utf8_decode(' '), 1, 0, 'C');

	$pdf->Ln();

	// Line 3
	$pdf->Cell(25, 12,utf8_decode('Nombre d\'avis'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Ecole d\'ingénieurs'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviIng[0] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviIng[1] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviIng[2] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviIng[3] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviIng[4] ), 1, 0, 'C');
	$pdf->Ln();

	// Line 3bis
	$pdf->Cell(25, 6, 'pour la promotion');
	$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviMast[0] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviMast[1] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviMast[2] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviMast[3] ), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode($nbAviMast[4] ), 1, 0, 'C');
	$pdf->Ln();

	// Line 3
	$pdf->Cell(25, 6,utf8_decode('Commentaire'), 1, 0, 'C');
	$pdf->Cell(150, 6,utf8_decode($avi['comm']), 1);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();


	$pdf->setX(150);
	$pdf->Cell(40, 6,utf8_decode('Signature du chef de Département'), 0, 0, 'L');
	$pdf->Ln();

	$pdf->setX(155);
	$pdf->Cell(40, 6,utf8_decode($chef), 0, 0, 'L');
	$pdf->Image($sign, 155, 245, 40); 

	$pdf->Ln();


	$title = $anneelib."_PoursuiteDEtude_" . $avi['nom'] . "_" . $avi['prenom'] . "_" . $avi['etdid'] . ".pdf";
	$pdf->Output($title, 'I');

    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
	$sql = "INSERT INTO Export (exportType, exportChemin) VALUES ('PE', '".$title."')";

	$db->execQuery($sql);
	
}


function generatePDFs() 
{
	// Lire le contenu du fichier JSON
	$jsonData = file_get_contents('../data/study.json');
	
	// Convertir la chaîne JSON en tableau associatif
	$data = json_decode($jsonData, true);

	$data = $data[0];

	// Vérifier si la conversion a réussi
	if ($data === null) {
		// Gérer l'erreur de décodage JSON
		echo "Erreur lors du décodage du fichier JSON.";
		return;
	}

	// Extraire les données nécessaires
	$logo1 = $data['logo1'];
	$logo2 = $data['logo2'];
	$chef = $data['chef'];
	$signature = $data['signature'];
	$anneeLib = $data['anneeLib'];

	$nbAviIng = $data['nbAvisIng'];
	$nbAviMast= $data['nbAvisMaster'];

	$avis = $data['avis'];

	foreach ($avis as $avi) 
	{
		generatePDF($avi, $anneeLib, $logo1, $logo2, $chef, $signature, $nbAviIng, $nbAviMast);
	}


	resetJSON();
}


function ajouterAvis($nouvelAvis)
{
	$cheminFichier = '../data/study.json';
	
	
	// Charger les données JSON existantes depuis le fichier
	$donnees = json_decode(file_get_contents($cheminFichier), true);

	// Ajouter le nouvel avis à la liste des avis
	$donnees['avis'][] = $nouvelAvis;

	//Ajouter les info en +
	$donnees['nbAvisMaster'][$nouvelAvis['avisMaster'   ]] = $donnees['nbAvisMaster'][$nouvelAvis['avisMaster'   ]]+1;
	$donnees['nbAvisIng'   ][$nouvelAvis['avisIngenieur']] = $donnees['nbAvisIng']   [$nouvelAvis['avisIngenieur']]+1;

	// Enregistrer les données mises à jour dans le fichier JSON
	file_put_contents($cheminFichier, json_encode($donnees, JSON_PRETTY_PRINT));
}


function ajouterInfo($logo1, $logo2, $chef, $signature, $anneeLib)
{
	$cheminFichier = '../data/study.json';
	
	
	// Charger les données JSON existantes depuis le fichier
	$donnees = json_decode(file_get_contents($cheminFichier), true);

	//Ajouter les info en +
	$donnees['logo1'    ] = $logo1;
	$donnees['logo2'    ] = $logo2;
	$donnees['chef'     ] = $chef;
	$donnees['signature'] = $signature;
	$donnees['anneeLib' ] = $anneeLib;

	$donnees['nbAvisMaster'] = [0,0,0,0,0];
	$donnees['nbAvisIng'   ] = [0,0,0,0,0];
	// Enregistrer les données mises à jour dans le fichier JSON
	file_put_contents($cheminFichier, json_encode($donnees, JSON_PRETTY_PRINT));
}

function resetJSON() 
{
	// Chemin vers le fichier JSON
	$cheminFichier = '../data/study.json';

	// Charger les données JSON existantes depuis le fichier
	$donnees = json_decode(file_get_contents($cheminFichier), true);
	$donnees = $donnees['0'];

	// Réinitialiser uniquement les avis à un tableau vide
	$donnees['avis'] = [];

	// Enregistrer les données mises à jour dans le fichier JSON
	$resultat = file_put_contents($cheminFichier, json_encode($donnees, JSON_PRETTY_PRINT));
}

function generateCSV(int $year, String $type, int $semester)
{
	// Creating the csv.json file
	generateStudentsCsv($year, $semester);

    // Set the appropriate headers for CSV file download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="PV_' . $type . '_S' . $semester . '.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Prepare and output CSV data
    if (strcmp($type, 'Commission') === 0)
	{
		$json_data = file_get_contents('../data/csv.json');
		$commissionData = json_decode($json_data, true);

        $header = headerCommission($commissionData[0]['competences']);
        fputcsv($output, $header); // Write CSV header

        $content = contentCommission($commissionData);
        fwrite($output, $content); // Write CSV content
    }

    if (strcmp($type, 'Jury') === 0 && $semester >= 2)
	{
		$json_data = file_get_contents('../data/csv.json');
		$juryData = json_decode($json_data, true);

        $header = headerJury($juryData[0]['competences']['RCUE'], $juryData[0]['competences']);
        fputcsv($output, $header); // Write CSV header

        $content = contentJury($juryData);
        fwrite($output, $content); // Write CSV content
    }

    // Close output stream
    fclose($output);

	echo $header;
	echo $content;
	
}

function headerCommission($competences)
{
	$header = '"Rg", "Nom", "Prenom", "Cursus", "Ues", "Moy"';

	foreach ($competences as $comp)
	{
		$header .= ', "'.$comp['compCode'].'", "Bonus '.$comp['compCode'].'"';
		
		foreach ($comp['modules'] as $mod)
		{
			$header .= ', "'.$mod['modCode'].'"';
		}		
	}

	return $header .'\n';
}


function headerJury($rcues, $competences)
{
	$header = '"Rg", "Nom", "Prenom", "Cursus"';
	
	foreach ($rcues as $rcue)
	{
		$header .= ', "C'.$rcue['compId'].'"';
	}

	$header .= ', "Ues", "Moy"';

	foreach ($competences as $comp)
	{
		$header .= ', "'.$comp['compCode'].'"';
	}

	return $header .'\n';
}

function contentJury($students)
{
	$studentInfo = "";

	// Iterating through the students of the specified year
	foreach ($students as $student)
	{
		$studentInfo .= '"'.
			$student['rank']      .'", "'.
			$student['etdNom']    .'", "'.
			$student['etdPrenom'] .'", "'.
			$student['etdCursus'] .'"';

		foreach ($student['RCUE'] as $mod)
		{
			$studentInfo .= '", "'.$mod['admi'];
		}

		$studentInfo .= '", "'.
			$student['admiUes'] .'", "'.
			$student['moySem'] .'"';

		foreach ($student['competences'] as $comp)
		{
			$studentInfo .= '", "'.$comp['moy'];
		}

		$studentInfo .= '\n';	
	}

	// print($studentInfo);
	return $studentInfo;
}

function contentCommission($students)
{
	$studentInfo = "";

	// Iterating through the students of the specified year
	foreach ($students as $student)
	{
		$studentInfo .= '"'.
			$student['rank']      .'", "'.
			$student['etdNom']    .'", "'.
			$student['etdPrenom'] .'", "'.
			$student['etdCursus'] .'", "'.
			$student['admiUEs']   .'", "'.
			$student['moySem']   .'"';

		foreach ($student['competences'] as $comp)
		{
			$studentInfo .= ', "'.$comp['moy'].'" ,"'.$student['etdBonus'].'"';

			foreach ($comp['modules'] as $mod)
			{
				$studentInfo .= ', "'.$mod['noteVal'].'"';
			}
		}

		$studentInfo .= '\n';
	}

	// print($studentInfo);
	return $studentInfo;
}
?>