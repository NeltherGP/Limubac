<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asistencia
 *
 * @ORM\Table(name="asistencia", indexes={@ORM\Index(name="id_asistencia", columns={"id_asistencia", "id_partido", "id_jugador"}), @ORM\Index(name="id_partido", columns={"id_partido"}), @ORM\Index(name="id_jugador", columns={"id_jugador"})})
 * @ORM\Entity
 */
class Asistencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_asistencia", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAsistencia;

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
     * Get idAsistencia
     *
     * @return integer 
     */
    public function getIdAsistencia()
    {
        return $this->idAsistencia;
    }

    /**
     * Set idJugador
     *
     * @param \limubac\administratorBundle\Entity\Jugador $idJugador
     * @return Asistencia
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
     * @return Asistencia
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
}
