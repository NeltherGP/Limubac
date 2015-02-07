<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * arbitran
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\arbitranRepository")
 */
class arbitran
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_arbitro1", type="integer")
     * @ORM\ManyToOne(targetEntity="arbitro", inversedBy="id")
     * @ORM\JoinColumn(name="id_arbitro1", referencedColumnName="id")
     */
    private $idArbitro1;

    /**
     * @var integer
     * 
     * @ORM\Column(name="id_arbitro2", type="integer")
     * @ORM\ManyToOne(targetEntity="arbitro")
     * @ORM\JoinColumn(name="id_arbitro2", referencedColumnName="id")
     */
    private $idArbitro2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_arbitro3", type="integer")
     * @ORM\ManyToOne(targetEntity="arbitro")
     * @ORM\JoinColumn(name="id_arbitro3", referencedColumnName="id")
     */
    private $idArbitro3;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idArbitro1
     *
     * @param integer $idArbitro1
     * @return arbitran
     */
    public function setIdArbitro1($idArbitro1)
    {
        $this->idArbitro1 = $idArbitro1;

        return $this;
    }

    /**
     * Get idArbitro1
     *
     * @return integer 
     */
    public function getIdArbitro1()
    {
        return $this->idArbitro1;
    }

    /**
     * Set idArbitro2
     *
     * @param integer $idArbitro2
     * @return arbitran
     */
    public function setIdArbitro2($idArbitro2)
    {
        $this->idArbitro2 = $idArbitro2;

        return $this;
    }

    /**
     * Get idArbitro2
     *
     * @return integer 
     */
    public function getIdArbitro2()
    {
        return $this->idArbitro2;
    }

    /**
     * Set idArbitro3
     *
     * @param integer $idArbitro3
     * @return arbitran
     */
    public function setIdArbitro3($idArbitro3)
    {
        $this->idArbitro3 = $idArbitro3;

        return $this;
    }

    /**
     * Get idArbitro3
     *
     * @return integer 
     */
    public function getIdArbitro3()
    {
        return $this->idArbitro3;
    }
}
