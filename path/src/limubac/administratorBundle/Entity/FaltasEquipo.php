<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaltasEquipo
 *
 * @ORM\Table(name="faltas_equipo", indexes={@ORM\Index(name="id_equipo", columns={"id_equipo", "id_partido", "id_jugador", "id_falta"}), @ORM\Index(name="id_partido", columns={"id_partido"}), @ORM\Index(name="id_jugador", columns={"id_jugador"}), @ORM\Index(name="id_falta", columns={"id_falta"}), @ORM\Index(name="IDX_7EFD2A19E2ABE6E6", columns={"id_equipo"})})
 * @ORM\Entity
 */
class FaltasEquipo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo", type="integer", nullable=true)
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_falta", type="string", length=255, nullable=false)
     */
    private $descFalta;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_faltas_eq", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFaltasEq;

    /**
     * @var \limubac\administratorBundle\Entity\Falta
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Falta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_falta", referencedColumnName="id_falta")
     * })
     */
    private $idFalta;

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
     * @var \limubac\administratorBundle\Entity\Partido
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Partido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_partido", referencedColumnName="id_partido")
     * })
     */
    private $idPartido;

    /**
     * @var \limubac\administratorBundle\Entity\Equipo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Equipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_equipo", referencedColumnName="id_equipo")
     * })
     */
    private $idEquipo;



    /**
     * Set tiempo
     *
     * @param integer $tiempo
     *
     * @return FaltasEquipo
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return integer
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set descFalta
     *
     * @param string $descFalta
     *
     * @return FaltasEquipo
     */
    public function setDescFalta($descFalta)
    {
        $this->descFalta = $descFalta;

        return $this;
    }

    /**
     * Get descFalta
     *
     * @return string
     */
    public function getDescFalta()
    {
        return $this->descFalta;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return FaltasEquipo
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
     * Get idFaltasEq
     *
     * @return integer
     */
    public function getIdFaltasEq()
    {
        return $this->idFaltasEq;
    }

    /**
     * Set idFalta
     *
     * @param \limubac\administratorBundle\Entity\Falta $idFalta
     *
     * @return FaltasEquipo
     */
    public function setIdFalta(\limubac\administratorBundle\Entity\Falta $idFalta = null)
    {
        $this->idFalta = $idFalta;

        return $this;
    }

    /**
     * Get idFalta
     *
     * @return \limubac\administratorBundle\Entity\Falta
     */
    public function getIdFalta()
    {
        return $this->idFalta;
    }

    /**
     * Set idJugador
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idJugador
     *
     * @return FaltasEquipo
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

    /**
     * Set idPartido
     *
     * @param \limubac\administratorBundle\Entity\Partido $idPartido
     *
     * @return FaltasEquipo
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
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     *
     * @return FaltasEquipo
     */
    public function setIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo = null)
    {
        $this->idEquipo = $idEquipo;

        return $this;
    }

    /**
     * Get idEquipo
     *
     * @return \limubac\administratorBundle\Entity\Equipo
     */
    public function getIdEquipo()
    {
        return $this->idEquipo;
    }
}
