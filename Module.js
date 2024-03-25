class Module
{
	Module(modId, modLib, modType)
	{
		this.modId = modId;
		this.modLib = modLib;
		this.modType = modType;
	}

	getModId  () { return modId; }
	getModLib () { return modLib; }
	getModType() { return modType; }
}