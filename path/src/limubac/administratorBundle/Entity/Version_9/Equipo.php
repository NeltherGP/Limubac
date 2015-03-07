<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipo
 *
 * @ORM\Table(name="equipo", indexes={@ORM\Index(name="id_capitan", columns={"id_capitan", "representante", "auxiliar"}), @ORM\Index(name="representante", columns={"representante"}), @ORM\Index(name="auxiliar", columns={"auxiliar"}), @ORM\Index(name="IDX_C49C530BEF72FA", columns={"id_capitan"})})
 * @ORM\Entity
 */
class Equipo
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=35, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_equipo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipo;

    /**
     * @var \limubac\administratorBundle\Entity\Jugador
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Jugador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auxiliar", referencedColumnName="id_jugador")
     * })
     */
    private $auxiliar;

    /**
     * @var \limubac\administratorBundle\Entity\Jugador
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Jugador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="representante", referencedColumnName="id_jugador")
     * })
     */
    private $representante;

    /**
     * @var \limubac\administratorBundle\Entity\Jugador
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Jugador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_capitan", referencedColumnName="id_jugador")
     * })
     */
    private $idCapitan;



    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Equipo
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
     * Get idEquipo
     *
     * @return integer 
     */
    public function getIdEquipo()
    {
        return $this->idEquipo;
    }

    /**
     * Set auxiliar
     *
     * @param \limubac\administratorBundle\Entity\Jugador $auxiliar
     * @return Equipo
     */
    public function setAuxiliar(\limubac\administratorBundle\Entity\Jugador $auxiliar = null)
    {
        $this->auxiliar = $auxiliar;

        return $this;
    }

    /**
     * Get auxiliar
     *
     * @return \limubac\administratorBundle\Entity\Jugador 
     */
    public function getAuxiliar()
    {
        return $this->auxiliar;
    }

    /**
     * Set representante
     *
     * @param \limubac\administratorBundle\Entity\Jugador $representante
     * @return Equipo
     */
    public function setRepresentante(\limubac\administratorBundle\Entity\Jugador $representante = null)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return \limubac\administratorBundle\Entity\Jugador 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set idCapitan
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idCapitan
     * @return Equipo
     */
    public function setIdCapitan(\limubac\administratorBundle\Entity\Jugador $idCapitan = null)
    {
        $this->idCapitan = $idCapitan;

        return $this;
    }

    /**
     * Get idCapitan
     *
     * @return \limubac\administratorBundle\Entity\Jugador 
     */
    public function getIdCapitan()
    {
        return $this->idCapitan;
    }
}
