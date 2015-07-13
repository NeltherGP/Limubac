<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BitacoraLimubac
 *
 * @ORM\Table(name="bitacora_limubac", indexes={@ORM\Index(name="id_bitacora", columns={"id_bitacora"})})
 * @ORM\Entity
 */
class BitacoraLimubac
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_dato", type="string", length=100, nullable=false)
     */
    private $nombreDato;

    /**
     * @var string
     *
     * @ORM\Column(name="evento", type="string", length=100, nullable=false)
     */
    private $evento;

    /**
     * @var string
     *
     * @ORM\Column(name="tabla", type="string", length=100, nullable=false)
     */
    private $tabla;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=100, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="aplicacion", type="string", length=100, nullable=false)
     */
    private $aplicacion;

    /**
     * @var string
     *
     * @ORM\Column(name="terminal", type="string", length=100, nullable=false)
     */
    private $terminal;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_bitacora", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBitacora;



    /**
     * Set nombreDato
     *
     * @param string $nombreDato
     *
     * @return BitacoraLimubac
     */
    public function setNombreDato($nombreDato)
    {
        $this->nombreDato = $nombreDato;

        return $this;
    }

    /**
     * Get nombreDato
     *
     * @return string
     */
    public function getNombreDato()
    {
        return $this->nombreDato;
    }

    /**
     * Set evento
     *
     * @param string $evento
     *
     * @return BitacoraLimubac
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return string
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set tabla
     *
     * @param string $tabla
     *
     * @return BitacoraLimubac
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get tabla
     *
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return BitacoraLimubac
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return BitacoraLimubac
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set aplicacion
     *
     * @param string $aplicacion
     *
     * @return BitacoraLimubac
     */
    public function setAplicacion($aplicacion)
    {
        $this->aplicacion = $aplicacion;

        return $this;
    }

    /**
     * Get aplicacion
     *
     * @return string
     */
    public function getAplicacion()
    {
        return $this->aplicacion;
    }

    /**
     * Set terminal
     *
     * @param string $terminal
     *
     * @return BitacoraLimubac
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * Get terminal
     *
     * @return string
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Get idBitacora
     *
     * @return integer
     */
    public function getIdBitacora()
    {
        return $this->idBitacora;
    }
}
