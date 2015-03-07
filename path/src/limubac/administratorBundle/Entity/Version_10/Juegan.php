<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Juegan
 *
 * @ORM\Table(name="juegan", indexes={@ORM\Index(name="id_juegan", columns={"id_juegan", "id_partido", "id_equipo"}), @ORM\Index(name="id_partido", columns={"id_partido"}), @ORM\Index(name="id_equipo", columns={"id_equipo"})})
 * @ORM\Entity
 */
class Juegan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="resultado", type="integer", nullable=false)
     */
    private $resultado;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", length=1, nullable=true)
     */
    private $side;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_juegan", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idJuegan;

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
     * @var \limubac\administratorBundle\Entity\Partido
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Partido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_partido", referencedColumnName="id_partido")
     * })
     */
    private $idPartido;



    /**
     * Set resultado
     *
     * @param integer $resultado
     * @return Juegan
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return integer 
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set side
     *
     * @param string $side
     * @return Juegan
     */
    public function setSide($side)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return string 
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Get idJuegan
     *
     * @return integer 
     */
    public function getIdJuegan()
    {
        return $this->idJuegan;
    }

    /**
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     * @return Juegan
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

    /**
     * Set idPartido
     *
     * @param \limubac\administratorBundle\Entity\Partido $idPartido
     * @return Juegan
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
