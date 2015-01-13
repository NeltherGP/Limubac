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
	protected $juez1;
	protected $juez2;
	protected $score;
	protected $faltas1T_1;
	protected $faltas1T_2;
	protected $faltas2T_1;
	protected $faltas2T_2;


	public function setEqA($name){
		$EqA=$name;
	}

	public function getEqA (){
		return $EqA;
	}

}

?>