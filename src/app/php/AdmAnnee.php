<?php
class AdmAnnee
{
	private int $etdId;
	private int $anneeId;
	private string $admi;

	public function __construct(int $etdId, int $anneeId, string $admi)
	{
		$this->etdId = $etdId;
		$this->anneeId = $anneeId;
		$this->admi = $admi;
	}

	// Getters and setters
}
?>