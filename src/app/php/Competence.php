<?php
class Competence
{
	private int    $compId;
	private string $compCode;
	private string $compLib;
	private int    $semId;

	public function __construct(int $compId, string $compCode, string $compLib, int $semId)
	{
		$this->compId = $compId;
		$this->compCode = $compCode;
		$this->compLib = $compLib;
		$this->semId = $semId;
	}

	// Getters and setters
}
?>