<?php
//src/Acme/TaskBundle/Form/Type/JugadorType.php
namespace limubac\administratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TorneoType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('idTorneo', 'hidden');
		$builder->add('nombre','text');
		$builder->add('costo','money',array("currency" => 'MXN'));
		$builder->add('fInicio','date', array('widget' => 'single_text','format' => 'yyyy-MM-dd',));
		$builder->add('fTermino','date', array('widget' => 'single_text','format' => 'yyyy-MM-dd',));
		$builder->add('inscripcionAbierta','integer');
	}

	public function getName(){
		return 'torneo';
	}

	/*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
        'data_class' => 'limubac\administratorBundle\Entity\Jugador',
    ));
    }*/
}
?>