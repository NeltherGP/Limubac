<?php
//src/Acme/TaskBundle/Form/Type/JugadorType.php
namespace limubac\administratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JugadorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('idJugador', 'hidden');
		$builder->add('nombre','text');
		$builder->add('apPaterno','text');
		$builder->add('apMaterno','text');
		$builder->add('fNacimiento','date', array('widget' => 'single_text','format' => 'yyyy-MM-dd',));
		$builder->add('correo','email');
		$builder->add('telefono','integer');
		$builder->add('profesion','text');
		$builder->add('estatura','text');
		$builder->add('peso','text');
		$builder->add('idFoto','hidden');
		$builder->add('idTiposanguineo','integer');
		$builder->add('idGenero','integer');
		$builder->add('idStatus','integer');
	}

	public function getName(){
		return 'jugador';
	}

	/*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
        'data_class' => 'limubac\administratorBundle\Entity\Jugador',
    ));
    }*/
}
?>