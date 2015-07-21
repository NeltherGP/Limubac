<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integra
 *
 * @ORM\Table(name="integra", indexes={@ORM\Index(name="id_jugador", columns={"id_jugador", "id_equipo"}), @ORM\Index(name="id_equipo", columns={"id_equipo"}), @ORM\Index(name="integra_ibfk_3", columns={"id_torneo"}), @ORM\Index(name="IDX_DB326E438CE0C668", columns={"id_jugador"})})
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
     * @var \limubac\administratorBundle\Entity\Torneo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Torneo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_torneo", referencedColumnName="id_torneo")
     * })
     */
    private $idTorneo;



    /**
     * Set noPlayera
     *
     * @param integer $noPlayera
     *
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
     * Set idJugador
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idJugador
     *
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

    /**
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     *
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
     * Set idTorneo
     *
     * @param \limubac\administratorBundle\Entity\Torneo $idTorneo
     *
     * @return Integra
     */
    public function setIdTorneo(\limubac\administratorBundle\Entity\Torneo $idTorneo = null)
    {
        $this->idTorneo = $idTorneo;

        return $this;
    }

    /**
     * Get idTorneo
     *
     * @return \limubac\administratorBundle\Entity\Torneo
     */
    public function getIdTorneo()
    {
        return $this->idTorneo;
    }
}
