<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipo
 *
 * @ORM\Table(name="equipo", indexes={@ORM\Index(name="id_capitan", columns={"id_capitan", "representante", "auxiliar"}), @ORM\Index(name="representante", columns={"representante"}), @ORM\Index(name="auxiliar", columns={"auxiliar"}), @ORM\Index(name="equipo_ibfk_4", columns={"id_rama"}), @ORM\Index(name="equipo_ibfk_5", columns={"id_categoria"}), @ORM\Index(name="IDX_C49C530BEF72FA", columns={"id_capitan"})})
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
     * @var boolean
     *
     * @ORM\Column(name="registrado", type="boolean", nullable=false)
     */
    private $registrado;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_equipo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipo;

    /**
     * @var \limubac\administratorBundle\Entity\Categoria
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Categoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categoria", referencedColumnName="id_categoria")
     * })
     */
    private $idCategoria;

    /**
     * @var \limubac\administratorBundle\Entity\RamaEquipo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\RamaEquipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_rama", referencedColumnName="id_rama")
     * })
     */
    private $idRama;

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
     * Set registrado
     *
     * @param boolean $registrado
     * @return Equipo
     */
    public function setRegistrado($registrado)
    {
        $this->registrado = $registrado;

        return $this;
    }

    /**
     * Get registrado
     *
     * @return boolean 
     */
    public function getRegistrado()
    {
        return $this->registrado;
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
     * Set idCategoria
     *
     * @param \limubac\administratorBundle\Entity\Categoria $idCategoria
     * @return Equipo
     */
    public function setIdCategoria(\limubac\administratorBundle\Entity\Categoria $idCategoria = null)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return \limubac\administratorBundle\Entity\Categoria 
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set idRama
     *
     * @param \limubac\administratorBundle\Entity\RamaEquipo $idRama
     * @return Equipo
     */
    public function setIdRama(\limubac\administratorBundle\Entity\RamaEquipo $idRama = null)
    {
        $this->idRama = $idRama;

        return $this;
    }

    /**
     * Get idRama
     *
     * @return \limubac\administratorBundle\Entity\RamaEquipo 
     */
    public function getIdRama()
    {
        return $this->idRama;
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
