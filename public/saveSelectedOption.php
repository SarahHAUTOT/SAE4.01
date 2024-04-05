<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	// Save the selected option in the $_SESSION variable
	
	if (isset($_POST["year"]))
	{
		$selectedYear = $_POST["year"];		
		$_SESSION["year"] = $selectedYear;
	}

	if (isset($_POST["semester"]))
	{
		$selectedSem = $_POST["semester"];
		$_SESSION["semester"] = $selectedSem;
	}
}
?>