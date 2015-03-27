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
    $builder->add('numero','integer');
		$builder->add('profesion','text');
		$builder->add('estatura','text');
		$builder->add('peso','text');
		//$builder->add('idFoto','hidden');
		//$builder->add('idTiposanguineo','integer');
		//$builder->add('idGenero','integer');
		$builder->add('idGenero', 'choice', array(
        'choices' => array(
          1 => 'Masculino',
          2 => 'Femenino',
          3 => 'Indistinto'
        ),
        'attr' => array('style' => 'width:158px'),
        'multiple' => false,
        'expanded' => false,
        'required' => true,
    	));
    	$builder->add('idTiposanguineo', 'choice', array(
        'choices' => array(
          1 => 'A+',
          2 => 'A-',
          3 => 'B+',
          4 => 'B-',
          5 => 'AB+',
          6 => 'AB-',
          7 => 'O+',
          8 => 'O-',
          9 => 'RH+',
          10 => 'RH-'
        ),
        'attr' => array('style' => 'width:108px'),
        'multiple' => false,
        'expanded' => false,
        'required' => true,
    	));
		//$builder->add('idStatus','integer');
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