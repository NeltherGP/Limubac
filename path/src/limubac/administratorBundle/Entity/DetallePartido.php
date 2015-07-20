<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetallePartido
 *
 * @ORM\Table(name="detalle_partido", indexes={@ORM\Index(name="id_jugador", columns={"id_jugador", "id_partido"}), @ORM\Index(name="id_partido", columns={"id_partido"}), @ORM\Index(name="IDX_6A79BA38CE0C668", columns={"id_jugador"})})
 * @ORM\Entity
 */
class DetallePartido
{
    /**
     * @var integer
     *
     * @ORM\Column(name="anotaciones", type="integer", nullable=false)
     */
    private $anotaciones;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_detalle", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDetalle;

    /**
     * @var \limubac\administratorBundle\Entity\Partido
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Partido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_partido", referencedColumnName="id_partido")
     * })
     */
    private $idPartido;

    /**
     * @var \limubac\administratorBundle\Entity\Jugador
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Jugador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_jugador", referencedColumnName="id_jugador")
     * })
     */
    private $idJugador;



    /**
     * Set anotaciones
     *
     * @param integer $anotaciones
     *
     * @return DetallePartido
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
     *
     * @return DetallePartido
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
     * Get idDetalle
     *
     * @return integer
     */
    public function getIdDetalle()
    {
        return $this->idDetalle;
    }

    /**
     * Set idPartido
     *
     * @param \limubac\administratorBundle\Entity\Partido $idPartido
     *
     * @return DetallePartido
     */
    public function setIdPartido(\limubac\administratorBundle\Entity\Partido $idPartido = null)
    {
        $this->idPartido = $idPartido;

        return $this;
    }

    /**
     * Get idPartido
     *
     * @return \limubac\administratorBundle\Entity\Partido
     */
    public function getIdPartido()
    {
        return $this->idPartido;
    }

    /**
     * Set idJugador
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idJugador
     *
     * @return DetallePartido
     */
    public function setIdJugador(\limubac\administratorBundle\Entity\Jugador $idJugador = null)
    {
        $this->idJugador = $idJugador;

        return $this;
    }

    /**
     * Get idJugador
     *
     * @return \limubac\administratorBundle\Entity\Jugador
     */
    public function getIdJugador()
    {
        return $this->idJugador;
    }
}
