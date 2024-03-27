<?php
class AdmComp
{
	private int $etdId;
	private int $compId;
	private int $anneeId;
	private string $admi;

	public function __construct(int $etdId, int $compId, int $anneeId, string $admi)
	{
		$this->etdId = $etdId;
		$this->compId = $compId;
		$this->anneeId = $anneeId;
		$this->admi = $admi;
	}

	// Getters and setters
}
?>