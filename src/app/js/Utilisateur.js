class Utilisateur 
{
	Utilisateur(userLogin, userPassword, userDroit)
	{
		this.userLogin = userLogin;
		this.userPassword = userPassword;
		this.userDroit = userDroit;
	}

	getCompId() { return this.compId; }
	getAdmi  () { return this.admi; }
	getSemId () { return this.semId; }
} 