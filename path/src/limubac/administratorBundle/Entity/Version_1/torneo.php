<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * torneo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\torneoRepository")
 */
class torneo
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float")
     */
    private $costo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_inicio", type="date")
     */
    private $fInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_termino", type="date")
     */
    private $fTermino;


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
     * Set nombre
     *
     * @param string $nombre
     * @return torneo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set costo
     *
     * @param float $costo
     * @return torneo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set fInicio
     *
     * @param \DateTime $fInicio
     * @return torneo
     */
    public function setFInicio($fInicio)
    {
        $this->fInicio = $fInicio;

        return $this;
    }

    /**
     * Get fInicio
     *
     * @return \DateTime 
     */
    public function getFInicio()
    {
        return $this->fInicio;
    }

    /**
     * Set fTermino
     *
     * @param \DateTime $fTermino
     * @return torneo
     */
    public function setFTermino($fTermino)
    {
        $this->fTermino = $fTermino;

        return $this;
    }

    /**
     * Get fTermino
     *
     * @return \DateTime 
     */
    public function getFTermino()
    {
        return $this->fTermino;
    }
}
