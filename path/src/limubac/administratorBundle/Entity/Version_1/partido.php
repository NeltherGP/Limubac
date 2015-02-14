<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * partido
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\partidoRepository")
 */
class partido
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
     * @ORM\Column(name="id_torneo", type="integer")
     */
    private $idTorneo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_arbitran", type="integer")
     */
    private $idArbitran;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_sede", type="integer")
     */
    private $idSede;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_inicio", type="time")
     */
    private $hInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_termino", type="time")
     */
    private $hTermino;


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
     * Set idTorneo
     *
     * @param integer $idTorneo
     * @return partido
     */
    public function setIdTorneo($idTorneo)
    {
        $this->idTorneo = $idTorneo;

        return $this;
    }

    /**
     * Get idTorneo
     *
     * @return integer 
     */
    public function getIdTorneo()
    {
        return $this->idTorneo;
    }

    /**
     * Set idArbitran
     *
     * @param integer $idArbitran
     * @return partido
     */
    public function setIdArbitran($idArbitran)
    {
        $this->idArbitran = $idArbitran;

        return $this;
    }

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
     * Set idSede
     *
     * @param integer $idSede
     * @return partido
     */
    public function setIdSede($idSede)
    {
        $this->idSede = $idSede;

        return $this;
    }

    /**
     * Get idSede
     *
     * @return integer 
     */
    public function getIdSede()
    {
        return $this->idSede;
    }

    /**
     * Set hInicio
     *
     * @param \DateTime $hInicio
     * @return partido
     */
    public function setHInicio($hInicio)
    {
        $this->hInicio = $hInicio;

        return $this;
    }

    /**
     * Get hInicio
     *
     * @return \DateTime 
     */
    public function getHInicio()
    {
        return $this->hInicio;
    }

    /**
     * Set hTermino
     *
     * @param \DateTime $hTermino
     * @return partido
     */
    public function setHTermino($hTermino)
    {
        $this->hTermino = $hTermino;

        return $this;
    }

    /**
     * Get hTermino
     *
     * @return \DateTime 
     */
    public function getHTermino()
    {
        return $this->hTermino;
    }
}
