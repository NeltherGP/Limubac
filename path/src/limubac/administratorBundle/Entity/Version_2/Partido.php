<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partido
 *
 * @ORM\Table(name="partido", indexes={@ORM\Index(name="id_torneo", columns={"id_torneo", "id_arbitran", "id_sede"}), @ORM\Index(name="id_arbitran", columns={"id_arbitran"}), @ORM\Index(name="id_sede", columns={"id_sede"}), @ORM\Index(name="IDX_4E79750B5ADCD613", columns={"id_torneo"})})
 * @ORM\Entity
 */
class Partido
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_inicio", type="time", nullable=false)
     */
    private $hInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_termino", type="time", nullable=false)
     */
    private $hTermino;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_partido", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPartido;

    /**
     * @var \limubac\administratorBundle\Entity\Sede
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Sede")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sede", referencedColumnName="id_sede")
     * })
     */
    private $idSede;

    /**
     * @var \limubac\administratorBundle\Entity\Arbitran
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Arbitran")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_arbitran", referencedColumnName="id_arbitran")
     * })
     */
    private $idArbitran;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="limubac\administratorBundle\Entity\Equipo", inversedBy="idPartido")
     * @ORM\JoinTable(name="juegan",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_partido", referencedColumnName="id_partido")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_equipo", referencedColumnName="id_equipo")
     *   }
     * )
     */
    private $idEquipo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idEquipo = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set hInicio
     *
     * @param \DateTime $hInicio
     * @return Partido
     */
    public function setHInicio($hInicio)
    {
        $this->hInicio = $hInicio;

        return $this;
    }

    /**
     * Get hInicio
     *
     * @return \DateTime 
     */
    public function getHInicio()
    {
        return $this->hInicio;
    }

    /**
     * Set hTermino
     *
     * @param \DateTime $hTermino
     * @return Partido
     */
    public function setHTermino($hTermino)
    {
        $this->hTermino = $hTermino;

        return $this;
    }

    /**
     * Get hTermino
     *
     * @return \DateTime 
     */
    public function getHTermino()
    {
        return $this->hTermino;
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
     * Set idSede
     *
     * @param \limubac\administratorBundle\Entity\Sede $idSede
     * @return Partido
     */
    public function setIdSede(\limubac\administratorBundle\Entity\Sede $idSede = null)
    {
        $this->idSede = $idSede;

        return $this;
    }

    /**
     * Get idSede
     *
     * @return \limubac\administratorBundle\Entity\Sede 
     */
    public function getIdSede()
    {
        return $this->idSede;
    }

    /**
     * Set idArbitran
     *
     * @param \limubac\administratorBundle\Entity\Arbitran $idArbitran
     * @return Partido
     */
    public function setIdArbitran(\limubac\administratorBundle\Entity\Arbitran $idArbitran = null)
    {
        $this->idArbitran = $idArbitran;

        return $this;
    }

    /**
     * Get idArbitran
     *
     * @return \limubac\administratorBundle\Entity\Arbitran 
     */
    public function getIdArbitran()
    {
        return $this->idArbitran;
    }

    /**
     * Set idTorneo
     *
     * @param \limubac\administratorBundle\Entity\Torneo $idTorneo
     * @return Partido
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

    /**
     * Add idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     * @return Partido
     */
    public function addIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo)
    {
        $this->idEquipo[] = $idEquipo;

        return $this;
    }

    /**
     * Remove idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     */
    public function removeIdEquipo(\limubac\administratorBundle\Entity\Equipo $idEquipo)
    {
        $this->idEquipo->removeElement($idEquipo);
    }

    /**
     * Get idEquipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdEquipo()
    {
        return $this->idEquipo;
    }
}
