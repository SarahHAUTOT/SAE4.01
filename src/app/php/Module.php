<?php
class Module
{
	private int $modId;
	private string $modLib;

	public function __construct(int $modId, string $modLib)
	{
		$this->modId = $modId;
		$this->modLib = $modLib;
	}

	// Getters and setters
}
?>