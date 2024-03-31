<?php
require '../DBtoJSON';
// function generateStudents(int $yearId, int $semesterId) dans DBtoJSON
// TODO deconstruct the JSON file
require '../../../data/csv.json';

function export(String $year, String $type, String $semester)
{
	header('Content-type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=PV '.$type.' '.$semester.' '.$year);
	
	$header = "";
	$rCompetence = "SELECT compId, compCode FROM Competence WHERE semId = ?"; //TODO DB request

	if (strcmp($type, 'Commission') !== 0) $header = headerCommission($competences);
	else $header = headerJury($competences);

	$rStudents = "SELECT nom, prenom, parcours FROM Etudiant WHERE anneeId = ?"; //TODO DB request

	// Iterating through the students of the specified year
	for ($i = 0; i < count($students); $i++)
	{
		$studentInfo =
			($i+1)              .'", "'.
			$student['nom']     .'", "'.
			$student['prenom']  .'", "'.
			$student['parcours'].'"';

		$rBonus = "SELECT etdBonus FROM Etudiant WHERE etdId = ?";

		for ($competences as $comp)
		{
			//TODO DB requests
			$rAdmUEs = "SELECT getNbAdminComp(?, ?, ?) FROM AdmComp"; //studentId, yearId, semId
			$rMoyUe  = "SELECT getCompMoy    (?, ?, ?) FROM Moyenne"; //compId, studentId, yearId
			$studentInfo .= ', "'.$admUEs.'" ,"'.', "'.$moyUe.'" ,"'.$bonus.'"'; // TODO change color of the moyUe cell

			for ($comp['modules'] as $modComp)
			{
				$rMoy = "SELECT noteVal FROM Moyenne WHERE anneId = ? AND modId = ? AND etdId = ?"; //TODO DB request

				if (is_null($rMoy)) $moy = 'NR';
				$studentInfo .= ', "'.$moy.'"';
			}
		}

		echo $studentInfo;
		print($studentInfo);
	}
}

function headerCommission($competences)
{
	$header = '"Rg","Nom","Prénom","Cursus","Ues","Moy"';
	for ($competences as $comp)
	{
		$header .= ', "'. $comp['compCode']. '", "Bonus '. $comp['compCode'].'"';

		$rModule = "SELECT c.modCode, modCoef, modId FROM Module m 
					JOIN CompMod c ON m.modId=c.modId
					WHERE compId = ?"; //TODO DB request
		
		$competences['modules'] = $modules;
		
		// Iterating through the modules of the comp
		for ($compMods as $compMod)
		{
			$header .= ', "'. $compMod['modCode']. '"';
		}
	}

	return $header .'\n';
}


function headerJury($competences)
{
	$header = '"code_nip, Rg","Nom","Prénom","Cursus"';
	
	for ($competences as $comp)
	{
		$header    .= ', "C'. $comp['compId'].slice(-1) .'"';
		$compCodes .= ', "'.$comp['compCode'].'"'; 
	}

	$header .= ', "Ues", "Moy", "'.$compCodes.'"';
	return $header .'\n';
}

function contentJury($competences)
function contentCommission($competences)
?>
