class Etudiant
{
	Etudiant(etudId, civilite, nom, prenom, gpTD, gpTP, bonus, bac)
	{
		this.etudId = etudId;
		this.civilite = civilite;
		this.nom = nom;
		this.prenom = prenom;
		this.gpTD = gpTD;
		this.gpTP = gpTP;
		this.bonus = bonus;
		this.bac = bac;
	}
	
	getId () { return etudId; }
	getNom() { return nom; }
	getPrenom  () { return prenom; }
	getCivilite() { return civilite; }

	getGpTD() { return gpTD; }
	getGpTP() { return gpTP; }
	
	getBac  () { return bac; }
	getBonus() { return bonus; }
}