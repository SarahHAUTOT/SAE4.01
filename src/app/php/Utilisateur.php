<?php
class Utilisateur
{
	private string $userLogin;
	private string $userPassword;
	private int $userDroit;

	public function __construct(string $userLogin, string $userPassword, int $userDroit)
	{
		$this->userLogin = $userLogin;
		$this->userPassword = $userPassword;
		$this->userDroit = $userDroit;
	}

	// Getters and setters
}
?>