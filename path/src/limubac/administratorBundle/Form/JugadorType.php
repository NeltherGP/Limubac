<?php
//src/Acme/TaskBundle/Form/Type/JugadorType.php
namespace limubac\administratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class JugadorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('idJugador', 'integer');
		$builder->add('nombre','text');
		$builder->add('apPaterno','text');
		$builder->add('apMaterno','text');
		$builder->add('fNacimiento','date');
		$builder->add('correo','email');
		$builder->add('telefono','integer');
		$builder->add('profesion','text');
		$builder->add('estatura','float');
		$builder->add('peso','float');
		$builder->add('foto','text');
		$builder->add('idTiposanguineo','integer');
		$builder->add('idGenero','integer');
		$builder->add('idStatus','integer');
	}

	public function getName(){
		return 'jugador';
	}
}
?>