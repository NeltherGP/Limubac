<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integra
 *
 * @ORM\Table(name="integra", indexes={@ORM\Index(name="IDX_DB326E43E2ABE6E6", columns={"id_equipo"}), @ORM\Index(name="IDX_DB326E438CE0C668", columns={"id_jugador"}), @ORM\Index(name="id_jugador", columns={"id_jugador", "id_equipo"})})
 * @ORM\Entity
 */
class Integra
{
    /**
     * @var integer
     *
     * @ORM\Column(name="no_playera", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $noPlayera;

    /**
     * @var \limubac\administratorBundle\Entity\Equipo
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="limubac\administratorBundle\Entity\Equipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_equipo", referencedColumnName="id_equipo")
     * })
     */
    private $idEquipo;

    /**
     * @var \limubac\administratorBundle\Entity\Jugador
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="limubac\administratorBundle\Entity\Jugador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_jugador", referencedColumnName="id_jugador")
     * })
     */
    private $idJugador;



    /**
     * Set noPlayera
     *
     * @param integer $noPlayera
     * @return Integra
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
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     * @return Integra
     */
    public function setIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo)
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

    /**
     * Set idJugador
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idJugador
     * @return Integra
     */
    public function setIdJugador(\limubac\administratorBundle\Entity\Jugador $idJugador)
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
