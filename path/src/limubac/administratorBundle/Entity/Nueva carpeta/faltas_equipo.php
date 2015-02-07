<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * faltas_equipo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\faltas_equipoRepository")
 */
class faltas_equipo
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
     * @ORM\Column(name="id_equipo", type="integer")
     */
    private $idEquipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_partido", type="integer")
     */
    private $idPartido;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_jugador", type="integer")
     */
    private $idJugador;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_falta", type="integer")
     */
    private $idFalta;

    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo", type="integer")
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_falta", type="string", length=255)
     */
    private $descFalta;


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
     * Set idEquipo
     *
     * @param integer $idEquipo
     * @return faltas_equipo
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

    /**
     * Set idPartido
     *
     * @param integer $idPartido
     * @return faltas_equipo
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

    /**
     * Set idJugador
     *
     * @param integer $idJugador
     * @return faltas_equipo
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
     * Set idFalta
     *
     * @param integer $idFalta
     * @return faltas_equipo
     */
    public function setIdFalta($idFalta)
    {
        $this->idFalta = $idFalta;

        return $this;
    }

    /**
     * Get idFalta
     *
     * @return integer 
     */
    public function getIdFalta()
    {
        return $this->idFalta;
    }

    /**
     * Set tiempo
     *
     * @param integer $tiempo
     * @return faltas_equipo
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
     * @return faltas_equipo
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
}
