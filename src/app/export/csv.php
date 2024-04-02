<?php
require '../DBtoJSON';

function export(String $year, String $type, String $semester)
{
	// CSV File creation
	header('Content-type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=PV '.$type.' '.$semester.' '.$year);
	
	$header  = null;
	$content = null;

	if (strcmp($type, 'Commission') === 0)
	{
		// function generateStudents(int $yearId, int $semesterId) dans DBtoJSON
		// Exploiting JSON File
		$json_data = file_get_contents('../../../data/csv.json');
		$commissionData = json_decode($json_data, true);

		$header  = headerCommission (...);
		$content = contentCommission($commissionData) ;
	}

	if (strcmp($type, 'Jury') === 0 && $semesterId >= 2)
	{
		// function generateStudents(int $yearId, int $semesterId) dans DBtoJSON
		// Exploiting JSON File
		$json_data = file_get_contents('../../../data/csv.json');
		$juryData = json_decode($json_data, true);
		
		$header  = headerJury (...);
		$content = contentJury($juryData); 
	}

	if (!is_null($header) && !is_null($content))
	{
		echo $header;
		echo $content;
	}
}

function headerCommission($competences)
{
	// TODO
	return $header .'\n';
}


function headerJury($competences)
{
	// TODO 
	return $header .'\n';
}

function contentJury($competences)
{
	$studentInfo = "";

	// Iterating through the students of the specified year
	foreach ($students as $student)
	{
		$studentInfo .=
			$student['rank']      .'", "'.
			$student['etdNom']    .'", "'.
			$student['etdPrenom'] .'", "'.
			$student['etdCursus'] .'"';

		foreach ($student['RCUE'] as $mod)
		{
			$studentInfo .= '", "'.$mod['admi'];
		}

		$studentInfo .= '", "'.
			$student['admiUes'] '", "'.
			$student['moySem'] .'"';*

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
		$studentInfo .=
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
				$studentInfo .= ', "'.$mod['moy'].'"';
			}
		}

		$studentInfo .= '\n';
	}

	// print($studentInfo);
	return $studentInfo;
}
?>
