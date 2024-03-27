<?php
class Moyenne
{
	private float $noteVal;
	private int   $etdId;
	private int   $modId;
	private int   $anneeId;

	public function __construct(float $noteVal, int $etdId, int $modId, int $anneeId)
	{
		$this->noteVal = $noteVal;
		$this->etdId = $etdId;
		$this->modId = $modId;
		$this->anneeId = $anneeId;
	}

	// Getters and setters
}
?>