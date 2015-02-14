<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * detalle_partido
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\detalle_partidoRepository")
 */
class detalle_partido
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
     * @ORM\Column(name="anotaciones", type="integer")
     */
    private $anotaciones;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_jugador", type="integer")
     */
    private $idJugador;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_partido", type="integer")
     */
    private $idPartido;


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
     * Set anotaciones
     *
     * @param integer $anotaciones
     * @return detalle_partido
     */
    public function setAnotaciones($anotaciones)
    {
        $this->anotaciones = $anotaciones;

        return $this;
    }

    /**
     * Get anotaciones
     *
     * @return integer 
     */
    public function getAnotaciones()
    {
        return $this->anotaciones;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return detalle_partido
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set idJugador
     *
     * @param integer $idJugador
     * @return detalle_partido
     */
    public function setIdJugador($idJugador)
    {
        $this->idJugador = $idJugador;

        return $this;
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
     * Set idPartido
     *
     * @param integer $idPartido
     * @return detalle_partido
     */
    public function setIdPartido($idPartido)
    {
        $this->idPartido = $idPartido;

        return $this;
    }

    /**
     * Get idPartido
     *
     * @return integer 
     */
    public function getIdPartido()
    {
        return $this->idPartido;
    }
}
