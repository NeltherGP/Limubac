<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Arbitran
 *
 * @ORM\Table(name="arbitran", indexes={@ORM\Index(name="id_arbitro1", columns={"id_arbitro1", "id_arbitro2", "id_arbitro3"}), @ORM\Index(name="id_arbitro2", columns={"id_arbitro2"}), @ORM\Index(name="id_arbitro3", columns={"id_arbitro3"}), @ORM\Index(name="IDX_B64E986F83C21511", columns={"id_arbitro1"})})
 * @ORM\Entity
 */
class Arbitran
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_arbitran", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArbitran;

    /**
     * @var \limubac\administratorBundle\Entity\Arbitro
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Arbitro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_arbitro3", referencedColumnName="id_arbitro")
     * })
     */
    private $idArbitro3;

    /**
     * @var \limubac\administratorBundle\Entity\Arbitro
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Arbitro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_arbitro2", referencedColumnName="id_arbitro")
     * })
     */
    private $idArbitro2;

    /**
     * @var \limubac\administratorBundle\Entity\Arbitro
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Arbitro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_arbitro1", referencedColumnName="id_arbitro")
     * })
     */
    private $idArbitro1;



    /**
     * Get idArbitran
     *
     * @return integer 
     */
    public function getIdArbitran()
    {
        return $this->idArbitran;
    }

    /**
     * Set idArbitro3
     *
     * @param \limubac\administratorBundle\Entity\Arbitro $idArbitro3
     * @return Arbitran
     */
    public function setIdArbitro3(\limubac\administratorBundle\Entity\Arbitro $idArbitro3 = null)
    {
        $this->idArbitro3 = $idArbitro3;

        return $this;
    }

    /**
     * Get idArbitro3
     *
     * @return \limubac\administratorBundle\Entity\Arbitro 
     */
    public function getIdArbitro3()
    {
        return $this->idArbitro3;
    }

    /**
     * Set idArbitro2
     *
     * @param \limubac\administratorBundle\Entity\Arbitro $idArbitro2
     * @return Arbitran
     */
    public function setIdArbitro2(\limubac\administratorBundle\Entity\Arbitro $idArbitro2 = null)
    {
        $this->idArbitro2 = $idArbitro2;

        return $this;
    }

    /**
     * Get idArbitro2
     *
     * @return \limubac\administratorBundle\Entity\Arbitro 
     */
    public function getIdArbitro2()
    {
        return $this->idArbitro2;
    }

    /**
     * Set idArbitro1
     *
     * @param \limubac\administratorBundle\Entity\Arbitro $idArbitro1
     * @return Arbitran
     */
    public function setIdArbitro1(\limubac\administratorBundle\Entity\Arbitro $idArbitro1 = null)
    {
        $this->idArbitro1 = $idArbitro1;

        return $this;
    }

    /**
     * Get idArbitro1
     *
     * @return \limubac\administratorBundle\Entity\Arbitro 
     */
    public function getIdArbitro1()
    {
        return $this->idArbitro1;
    }
}
