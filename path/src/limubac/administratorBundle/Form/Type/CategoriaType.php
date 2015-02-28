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