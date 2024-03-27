<?php
class Module
{
	private int    $modId;
	private string $modCode;
	private string $modCat;
	private string $modLib;

	public function __construct(int $modId,      string $modCat,
								string $modCode, string $modLib)
	{
		$this->modId  = $modId;
		$this->modCat = $modCat;
		$this->modLib = $modLib;
		$this->modCode = $modCode;
	}

	// Getters and setters
}
?>