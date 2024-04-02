<?php
include "background.php";
session_start();

$jsonData = file_get_contents('../data/users.json');

$data = json_decode($jsonData, true);


// Vérifier si les champs du formulaire sont soumis
if (isset($_POST['user']) && isset($_POST['password'])) {
	$username = $_POST['user'];
	$password = $_POST['password'];

	foreach ($data as $user) {
		if ($user['userlogin'] === $username && $user['userpassword'] === $password) {
			$_SESSION['username'] = $username;
			$_SESSION['role'] = $user['userdroit'];

			if ( $user['userdroit'] == 2)
				header("Location: ./accueilAdmin.php");
			else
				header("Location: ./accueilUtilisateur.php");

			exit;
		}
	}
}
function contenu(){echo'
<h1>Connexion</h1>
<div class="container">
	<form method="POST" action="">
	<div class="gridRessource">
		<span>Utilisateur :</span>
		<input id="userName" type="text" name="user" value="">
		<span>Mot de passe :</span>
		<input id="password" type="password" name="password" value="">

	</div>
	<button type="submit" class="validateButtonStyle">Valider</button>
	</form>
</div>';};




head("css/accueilEtConnexion.css");

contenu();

foot();
?>