class Note
{
	Note(noteId, etudId, modId, valeur)
	{
		this.noteId = noteId;
		this.etudId = etudId;
		this.modId = modId;
		this.valeur = valeur;
	}

	getId    () { return noteId; }
	getEtudId() { return etudId; }
	getModId () { return modId; }
}
