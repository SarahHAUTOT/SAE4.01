<?php
// Inclure le fichier fpdf.php
require('../../../lib/fpdf/fpdf.php');

// Créer une nouvelle instance de FPDF
$pdf = new FPDF();

// Ajouter une page au document
$pdf->AddPage();




/*                */
/* Première Ligne */
/*                */

//Logo 1
$pdf->Image('Logo1.png', 10, 15, 50); 
// Logo 2
$pdf->Image('Logo2.png', 160, 10, 23); 
// Titre
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(40);
$pdf->Cell(0, -1, utf8_decode("Fiche d'avis poursuite d'études - Promotion 2022-2023"), 0, 1, 'C');
$pdf->SetY(45);
$pdf->Cell(0, -1, utf8_decode("Département Informatique IUT Le Havre"), 0, 1, 'C');




/*                  */
/* Première Tableau */
/*                  */

// Titre
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(50);
$pdf->Cell(0, 10, utf8_decode("FICHE D'INFORMATION ETUDIANT(E)"), 0, 1, 'L');
$pdf->Line(10, 57, 190, 57);

$pdf->SetFont('Arial', '', 8);

//Ligne 1
$pdf->Cell( 60, 4, utf8_decode("NOM - Prénom :"), 1);
$pdf->Cell(120, 4, '', 1);
$pdf->Ln();

//Ligne 2
$pdf->Cell(60, 4, utf8_decode("Apprentissage : (oui/non)"), 1);
$pdf->Cell(10, 4, 'BUT1', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Cell(10, 4, 'BUT2', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Cell(10, 4, 'BUT3', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Ln();

//Ligne 3
$pdf->Cell(60, 4, utf8_decode("Parcours d'étude"), 1);
$pdf->Cell(10, 4, 'n-2', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Cell(10, 4, 'n-1', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Cell(10, 4, 'n', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Ln();

//Ligne 1
$pdf->Cell(60 , 4, utf8_decode("Parcours BUT"), 1);
$pdf->Cell(120, 4, utf8_decode("A \"Réalisation d'applications : conception, développement, validation\""), 1);
$pdf->Ln();

//Ligne 1
$pdf->Cell(60 , 4, utf8_decode("Si mobilité à l'étranger (lieu,durée)"), 1);
$pdf->Cell(120, 4, '', 1);
$pdf->Ln();



/*                  */
/* Deuxième Tableau */
/*                  */

// Titre
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(85);
$pdf->Cell(0, 10, utf8_decode("RESULTATS DES COMPETENCES"), 0, 1, 'L');
$pdf->Line(10, 92, 190, 92);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(220, 220, 220);

//Ligne 1
$pdf->Cell(65, 4, '', 0);
$pdf->Cell(30, 4, 'BUT1', 1, 0, 'C', true);
$pdf->Cell(30, 4, 'BUT2', 1, 0, 'C', true);
$pdf->Ln();
$pdf->SetFont('Arial', '', 8);

//Ligne 2
$pdf->Cell(65, 4, ''    , 0);
$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
$pdf->Ln();

//Ligne 3
$pdf->Cell(65, 4, utf8_decode('UE1 - Réaliser des applications')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 4
$pdf->Cell(65, 4, utf8_decode('UE2 - Optimiser des applications')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 4
$pdf->Cell(65, 4, utf8_decode('UE3 - Administrer des systèmes')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 6
$pdf->Cell(65, 4, utf8_decode('UE4 - Gérer desdonnées')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 7
$pdf->Cell(65, 4, utf8_decode('UE5 - Conduire des projets')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 8
$pdf->Cell(65, 4, utf8_decode('UE6 - Collaborer')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 9
$pdf->Cell(65, 4, utf8_decode('Maths')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 10
$pdf->Cell(65, 4, utf8_decode('Anglais')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 11
$pdf->Cell(65, 4, utf8_decode('Nombres d\'absences injustifiés')    , 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Ln();



/*                   */
/* Troisième Tableau */
/*                   */
$pdf->Ln();

//Ligne 1
$pdf->Cell(65, 4, '', 0);
$pdf->Cell(30, 4, 'BUT3', 1, 0, 'C', true);
$pdf->Ln();
$pdf->SetFont('Arial', '', 8);

//Ligne 2
$pdf->Cell(65, 4, ''    , 0);
$pdf->Cell(15, 4, 'Moy.', 1, 0, 'L', true);
$pdf->Cell(15, 4, 'Rang', 1, 0, 'L', true);
$pdf->Ln();

//Ligne 3
$pdf->Cell(65, 4, utf8_decode('UE1 - Réaliser des applications')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 4
$pdf->Cell(65, 4, utf8_decode('UE2 - Optimiser des applications')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 5
$pdf->Cell(65, 4, utf8_decode('UE6 - Collaborer')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 6
$pdf->Cell(65, 4, utf8_decode('Maths')    , 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Cell(15, 4, '', 1);
$pdf->Ln();

//Ligne 7
$pdf->Cell(65, 4, utf8_decode('Nombres d\'absences injustifiés')    , 1);
$pdf->Cell(30, 4, '', 1);
$pdf->Ln();



/*                 */
/* Dernier Tableau */
/*                 */

// Titre
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(175);
$pdf->Cell(0, 10, utf8_decode("Avis de l'équipe pédagogique pour la poursuite d'étude après le BUT3"), 0, 1, 'C');
$pdf->Line(10, 182, 190, 182);

$pdf->SetFont('Arial', '', 8);

//Ligne 1
$pdf->Cell(25, 6, '', 1, 0, 'C');
$pdf->Cell(25, 6, '', 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Très Favorable'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Favorable'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Assez Favorable'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Sans avis'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Réservé'), 1, 0, 'C');
$pdf->Ln();

//Ligne 2
$pdf->Cell(25, 12,utf8_decode('Pour l\'étudiant'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Ecole d\'ingénieurs'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Ln();

//Ligne 2bis
$pdf->Cell(25, 6);
$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('o'), 1, 0, 'C');
$pdf->Ln();

//Ligne 3
$pdf->Cell(25, 12,utf8_decode('Nombre d\'avis'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Ecole d\'ingénieurs'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Ln();

//Ligne 3bis
$pdf->Cell(25, 6, 'pour la promotion');
$pdf->Cell(25, 6, utf8_decode('Master'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode(''), 1, 0, 'C');
$pdf->Ln();

//Ligne 3
$pdf->Cell(25, 6,utf8_decode('Commentaire'), 1, 0, 'C');
$pdf->Cell(150, 6, '', 1);
$pdf->Ln();






$pdf->Output('image.pdf', 'I');

?>
