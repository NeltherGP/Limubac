<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jugador
 *
 * @ORM\Table(name="jugador", indexes={@ORM\Index(name="id_status", columns={"id_status", "id_genero", "id_tiposanguineo", "id_foto"}), @ORM\Index(name="id_genero", columns={"id_genero"}), @ORM\Index(name="id_tiposanguineo", columns={"id_tiposanguineo"}), @ORM\Index(name="id_foto", columns={"id_foto"}), @ORM\Index(name="IDX_527D6F185D37D0F1", columns={"id_status"})})
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
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion", type="string", length=35, nullable=true)
     */
    private $profesion;

    /**
     * @var float
     *
     * @ORM\Column(name="estatura", type="float", precision=10, scale=0, nullable=true)
     */
    private $estatura;

    /**
     * @var float
     *
     * @ORM\Column(name="peso", type="float", precision=10, scale=0, nullable=true)
     */
    private $peso;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_jugador", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idJugador;

    /**
     * @var \limubac\administratorBundle\Entity\Fotos
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Fotos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_foto", referencedColumnName="id_foto")
     * })
     */
    private $foto;

    /**
     * @var \limubac\administratorBundle\Entity\TipoSanguineo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\TipoSanguineo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tiposanguineo", referencedColumnName="id_tiposanguineo")
     * })
     */
    private $idTiposanguineo;

    /**
     * @var \limubac\administratorBundle\Entity\Genero
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Genero")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genero", referencedColumnName="id_genero")
     * })
     */
    private $idGenero;

    /**
     * @var \limubac\administratorBundle\Entity\Status
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id_status")
     * })
     */
    private $idStatus;



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
     * Set estatura
     *
     * @param float $estatura
     * @return Jugador
     */
    public function setEstatura($estatura)
    {
        $this->estatura = $estatura;

        return $this;
    }

    /**
     * Get estatura
     *
     * @return float 
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * Set peso
     *
     * @param float $peso
     * @return Jugador
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return float 
     */
    public function getPeso()
    {
        return $this->peso;
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
     * Set foto
     *
     * @param \limubac\administratorBundle\Entity\Fotos $foto
     * @return Jugador
     */
    public function setfoto(\limubac\administratorBundle\Entity\Fotos $foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \limubac\administratorBundle\Entity\Fotos 
     */
    public function getfoto()
    {
        return $this->foto;
    }

    /**
     * Set idTiposanguineo
     *
     * @param \limubac\administratorBundle\Entity\TipoSanguineo $idTiposanguineo
     * @return Jugador
     */
    public function setIdTiposanguineo(\limubac\administratorBundle\Entity\TipoSanguineo $idTiposanguineo = null)
    {
        $this->idTiposanguineo = $idTiposanguineo;

        return $this;
    }

    /**
     * Get idTiposanguineo
     *
     * @return \limubac\administratorBundle\Entity\TipoSanguineo 
     */
    public function getIdTiposanguineo()
    {
        return $this->idTiposanguineo;
    }

    /**
     * Set idGenero
     *
     * @param \limubac\administratorBundle\Entity\Genero $idGenero
     * @return Jugador
     */
    public function setIdGenero(\limubac\administratorBundle\Entity\Genero $idGenero = null)
    {
        $this->idGenero = $idGenero;

        return $this;
    }

    /**
     * Get idGenero
     *
     * @return \limubac\administratorBundle\Entity\Genero 
     */
    public function getIdGenero()
    {
        return $this->idGenero;
    }

    /**
     * Set idStatus
     *
     * @param \limubac\administratorBundle\Entity\Status $idStatus
     * @return Jugador
     */
    public function setIdStatus(\limubac\administratorBundle\Entity\Status $idStatus = null)
    {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idStatus
     *
     * @return \limubac\administratorBundle\Entity\Status 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }
}
