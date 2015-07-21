<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Torneo
 *
 * @ORM\Table(name="torneo", indexes={@ORM\Index(name="id_torneo", columns={"id_torneo"})})
 * @ORM\Entity
 */
class Torneo
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float", precision=10, scale=0, nullable=false)
     */
    private $costo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_inicio", type="date", nullable=false)
     */
    private $fInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_termino", type="date", nullable=false)
     */
    private $fTermino;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inscripcion_abierta", type="boolean", nullable=false)
     */
    private $inscripcionAbierta = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="id_torneo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTorneo;



    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Torneo
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
     *
     * @return Torneo
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
     *
     * @return Torneo
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
     *
     * @return Torneo
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

    /**
     * Set inscripcionAbierta
     *
     * @param boolean $inscripcionAbierta
     *
     * @return Torneo
     */
    public function setInscripcionAbierta($inscripcionAbierta)
    {
        $this->inscripcionAbierta = $inscripcionAbierta;

        return $this;
    }

    /**
     * Get inscripcionAbierta
     *
     * @return boolean
     */
    public function getInscripcionAbierta()
    {
        return $this->inscripcionAbierta;
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
}
