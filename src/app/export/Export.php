<?php

require('../../../lib/fpdf/fpdf.php');

function generatePDF($avi, $anneelib, $logo1, $logo2, $chef, $sign)
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
	$pdf->Cell(120, 4, $avi['mobilite'], 1);
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
	$pdf->Cell(15, 4, '', 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Ln();

	// Line 4
	$pdf->Cell(65, 4, utf8_decode('UE2 - Optimiser des applications')    , 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Ln();

	// Line 5
	$pdf->Cell(65, 4, utf8_decode('UE6 - Collaborer')    , 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Ln();

	// Line 6
	$pdf->Cell(65, 4, utf8_decode('Maths')    , 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Cell(15, 4, '', 1);
	$pdf->Ln();

	// Line 7
	$pdf->Cell(65, 4, utf8_decode('Nombres d\'absences injustifiés')    , 1);
	$pdf->Cell(30, 4, '', 1);
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
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Ln();

	// Line 2bis
	$pdf->Cell(25, 6);
	$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
	$pdf->Ln();

	// Line 3
	$pdf->Cell(25, 12,utf8_decode('Nombre d\'avis'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode('Ecole d\'ingénieurs'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Ln();

	// Line 3bis
	$pdf->Cell(25, 6, 'pour la promotion');
	$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
	$pdf->Ln();

	// Line 3
	$pdf->Cell(25, 6,utf8_decode('Commentaire'), 1, 0, 'C');
	$pdf->Cell(150, 6, '', 1);
	$pdf->Ln();

	$pdf->Output('image.pdf', 'I');
}


function generatePDFs() 
{
    // Lire le contenu du fichier JSON
    $jsonData = file_get_contents('../../../data/study.json');
    
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

    $avis = $data['avis'];

    foreach ($avis as $avi) 
    {
        generatePDF($avi, $anneeLib, $logo1, $logo2, $chef, $signature);
    }
}

generatePDFs();


?>