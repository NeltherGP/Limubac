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
		$builder->add('inscripcionAbierta','choice', array(
			'choices' => array(
				1 => 'Abierta',
				0 => 'Cerrada'),
			'attr' => array('style' => 'width:158px'),
			'multiple' => false,
			'expanded' => false,
			'required' => true,
			));
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