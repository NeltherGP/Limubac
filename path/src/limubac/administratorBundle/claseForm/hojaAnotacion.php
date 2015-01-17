<?php

namespace limubac\administratorBundle\claseForm;

class hojaAnotacion {

	protected $EqA;
	protected $EqB;
	protected $Rama;
	protected $Categoria;
	protected $Lugar;
	protected $Torneo;
	protected $Fecha;
	protected $Hora;
	protected $Juez1;
	protected $Juez2;
	protected $Score;
	protected $faltas1T_1;
	protected $faltas1T_2;
	protected $faltas2T_1;
	protected $faltas2T_2;


	public function setEqA($name){
		$this->EqA=$name;

	}

	public function getEqA (){
		return $this->EqA;
	}

	public function setEqB($name){
		$this->EqB=$name;

	}

	public function getEqB (){
		return $this->EqB;
	}

	public function setRama ($rama){
		$this->Rama=$rama;
	}

	public function getRama (){
		return $this->Rama;
	}

	public function setCategoria ($categoria){
		$this->Categoria=$categoria;
	}

	public function getCategoria (){
		return $this->Categoria;
	}

	public function setLugar ($lugar){
		$this->Lugar=$lugar;
	}

	public function getLugar (){
		return $this->Lugar;
	}

	public function setTorneo ($torneo){
		$this->Torneo=$torneo;
	}

	public function getTorneo (){
		return $this->Torneo;
	}

	public function setFecha ($fecha){
		$this->Fecha=$fecha;
	}

	public function getFecha (){
		return $this->Fecha;
	}

	public function setHora ($hora){
		$this->Hora=$hora;
	}
	
	public function getHora (){
		return $this->Hora;
	}

	public function setJuez1 ($juez1){
		$this->Juez1=$juez1;
	}

	public function getJuez1 (){
		return $this->Juez1;
	}

	public function setJuez2 ($juez2){
		$this->Juez2=$juez2;
	}

	public function getJuez2 (){
		return $this->Juez2;
	}

	


}

?>