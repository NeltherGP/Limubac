<?php
//src/Acme/TaskBundle/Form/Type/JugadorType.php
namespace limubac\administratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('idCategoria', 'hidden');
		$builder->add('nombre','text');
		$builder->add('edad','integer');
		$builder->add('limiteEquipo','integer');
		$builder->add('refEdad','choice', array(
			'choices' => array(
				1 => 'Mayor >',
				0 => 'Menor <'),
			'attr' => array('style' => 'width:158px'),
			'multiple' => false,
			'expanded' => false,
			'required' => true,
			));
	}

	public function getName(){
		return 'categoria';
	}

	/*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
        'data_class' => 'limubac\administratorBundle\Entity\Jugador',
    ));
    }*/
}
?>