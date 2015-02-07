<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * integra
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\integraRepository")
 */
class integra
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
     * @ORM\Column(name="id_jugador", type="integer")
     */
    private $idJugador;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_equipo", type="integer")
     */
    private $idEquipo;


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
     * Set idJugador
     *
     * @param integer $idJugador
     * @return integra
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
     * Set idEquipo
     *
     * @param integer $idEquipo
     * @return integra
     */
    public function setIdEquipo($idEquipo)
    {
        $this->idEquipo = $idEquipo;

        return $this;
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
}
