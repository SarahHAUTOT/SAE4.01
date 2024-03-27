<?php
class CompMod
{
	private int   $compId;
	private int   $modId;
	private float $modCoef;

	public function __construct(int $compId, int $modId, float $modCoef)
	{
		$this->compId = $compId;
		$this->modId = $modId;
		$this->modCoef = $modCoef;
	}

	// Getters and setters
}
?>