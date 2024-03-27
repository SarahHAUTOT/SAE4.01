<?php
class Competence
{
	private int $compId;
	private string $compLib;
	private int $semId;

	public function __construct(int $compId, string $compLib, int $semId)
	{
		$this->compId = $compId;
		$this->compLib = $compLib;
		$this->semId = $semId;
	}

	// Getters and setters
}
?>