<?php
class Module
{
	private int $modId;
	private string $modCode;
	private string $modLib;

	public function __construct(int $modId, string $modCode, string $modLib)
	{
		$this->modId  = $modId;
		$this->modLib = $modLib;
		$this->modCode = $modCode;
	}

	// Getters and setters
}
?>