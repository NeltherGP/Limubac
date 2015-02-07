<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jugador
 *
 * @ORM\Table(name="jugador")
 * @ORM\Entity
 */
class Jugador
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=35, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="ap_paterno", type="string", length=35, nullable=false)
     */
    private $apPaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="ap_materno", type="string", length=35, nullable=false)
     */
    private $apMaterno;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_nacimiento", type="date", nullable=false)
     */
    private $fNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=35, nullable=false)
     */
    private $correo;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_playera", type="integer", nullable=false)
     */
    private $noPlayera;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion", type="string", length=35, nullable=false)
     */
    private $profesion;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=25, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=9, nullable=false)
     */
    private $genero;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=false)
     */
    private $foto;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_jugador", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idJugador;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="limubac\administratorBundle\Entity\Equipo", inversedBy="idJugador")
     * @ORM\JoinTable(name="integra",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_jugador", referencedColumnName="id_jugador")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_equipo", referencedColumnName="id_equipo")
     *   }
     * )
     */
    private $idEquipo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idEquipo = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Jugador
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
     * Set apPaterno
     *
     * @param string $apPaterno
     * @return Jugador
     */
    public function setApPaterno($apPaterno)
    {
        $this->apPaterno = $apPaterno;

        return $this;
    }

    /**
     * Get apPaterno
     *
     * @return string 
     */
    public function getApPaterno()
    {
        return $this->apPaterno;
    }

    /**
     * Set apMaterno
     *
     * @param string $apMaterno
     * @return Jugador
     */
    public function setApMaterno($apMaterno)
    {
        $this->apMaterno = $apMaterno;

        return $this;
    }

    /**
     * Get apMaterno
     *
     * @return string 
     */
    public function getApMaterno()
    {
        return $this->apMaterno;
    }

    /**
     * Set fNacimiento
     *
     * @param \DateTime $fNacimiento
     * @return Jugador
     */
    public function setFNacimiento($fNacimiento)
    {
        $this->fNacimiento = $fNacimiento;

        return $this;
    }

    /**
     * Get fNacimiento
     *
     * @return \DateTime 
     */
    public function getFNacimiento()
    {
        return $this->fNacimiento;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Jugador
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set noPlayera
     *
     * @param integer $noPlayera
     * @return Jugador
     */
    public function setNoPlayera($noPlayera)
    {
        $this->noPlayera = $noPlayera;

        return $this;
    }

    /**
     * Get noPlayera
     *
     * @return integer 
     */
    public function getNoPlayera()
    {
        return $this->noPlayera;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Jugador
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set profesion
     *
     * @param string $profesion
     * @return Jugador
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string 
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Jugador
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set genero
     *
     * @param string $genero
     * @return Jugador
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero
     *
     * @return string 
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Jugador
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Get idJugador
     *
     * @return integer 
     */
    public function getIdJugador()
    {
        return $this->idJugador;
    }

    /**
     * Add idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     * @return Jugador
     */
    public function addIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo)
    {
        $this->idEquipo[] = $idEquipo;

        return $this;
    }

    /**
     * Remove idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     */
    public function removeIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo)
    {
        $this->idEquipo->removeElement($idEquipo);
    }

    /**
     * Get idEquipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdEquipo()
    {
        return $this->idEquipo;
    }
}
