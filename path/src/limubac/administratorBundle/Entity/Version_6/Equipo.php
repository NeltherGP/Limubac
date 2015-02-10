<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipo
 *
 * @ORM\Table(name="equipo", indexes={@ORM\Index(name="IDX_C49C530B407BE8B2", columns={"auxiliar"}), @ORM\Index(name="IDX_C49C530BDE2D595", columns={"representante"}), @ORM\Index(name="IDX_C49C530BEF72FA", columns={"id_capitan"}), @ORM\Index(name="id_capitan", columns={"id_capitan", "representante", "auxiliar"})})
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
     *   @ORM\JoinColumn(name="id_capitan", referencedColumnName="id_jugador")
     * })
     */
    private $idCapitan;

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
     *   @ORM\JoinColumn(name="auxiliar", referencedColumnName="id_jugador")
     * })
     */
    private $auxiliar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="limubac\administratorBundle\Entity\Partido", mappedBy="idEquipo")
     */
    private $idPartido;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idPartido = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Add idPartido
     *
     * @param \limubac\administratorBundle\Entity\Partido $idPartido
     * @return Equipo
     */
    public function addIdPartido(\limubac\administratorBundle\Entity\Partido $idPartido)
    {
        $this->idPartido[] = $idPartido;

        return $this;
    }

    /**
     * Remove idPartido
     *
     * @param \limubac\administratorBundle\Entity\Partido $idPartido
     */
    public function removeIdPartido(\limubac\administratorBundle\Entity\Partido $idPartido)
    {
        $this->idPartido->removeElement($idPartido);
    }

    /**
     * Get idPartido
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdPartido()
    {
        return $this->idPartido;
    }
}
