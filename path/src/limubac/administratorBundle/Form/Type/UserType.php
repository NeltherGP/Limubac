<?php
//src/Acme/TaskBundle/Form/Type/JugadorType.php
namespace limubac\administratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('id', 'hidden');
		$builder->add('username', 'text');
		$builder->add('password','password');
		$builder->add('email','email');
		$builder->add('isActive','text');
		$builder->add('address','text');		
		$builder->add('phone','integer');
		$builder->add('roles','text');
		$builder->add('name','text');
		//$builder->add('inscripcionAbierta','integer');
		
	}

	public function getName(){
		return 'user';
	}

	/*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
        'data_class' => 'limubac\administratorBundle\Entity\Jugador',
    ));
    }*/
}
?>