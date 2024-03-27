<?php
class Etudiant
{
	private int    $etdId;
	private string $etdCiv;
	private string $etdNom;
	private string $etdPrenom;
	private string $etdGroupeTP;
	private string $etdGroupeTD;
	private string $etdParcours;
	private float  $etdBonus;
	private int    $etdAbs;
	private string $etdBac;

	public function __construct(int $etdId,          string $etdCiv,      string $etdNom, 
								string $etdPrenom,   string $etdGroupeTP, string $etdGroupeTD,
								string $etdParcours, float $etdBonus,     string $etdAbs,
								string $etdBac)
	{
		$this->etdId = $etdId;
		$this->etdCiv = $etdCiv;
		$this->etdNom = $etdNom;
		$this->etdPrenom   = $etdPrenom;
		$this->etdGroupeTP = $etdGroupeTP;
		$this->etdGroupeTD = $etdGroupeTD;
		$this->etdParcours = $etdParcours;
		$this->etdBonus = $etdBonus;
		$this->etdBac   = $etdBac;
		$this->etdAbs   = $etdAbs;
	}

	// Getters and setters
}
?>