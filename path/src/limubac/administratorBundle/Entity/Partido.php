<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partido
 *
 * @ORM\Table(name="partido", indexes={@ORM\Index(name="id_torneo", columns={"id_torneo", "id_arbitran", "id_sede"}), @ORM\Index(name="id_arbitran", columns={"id_arbitran"}), @ORM\Index(name="id_sede", columns={"id_sede"}), @ORM\Index(name="partido_ibfk_4", columns={"id_estatus"}), @ORM\Index(name="IDX_4E79750B5ADCD613", columns={"id_torneo"})})
 * @ORM\Entity
 */
class Partido
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_inicio", type="time", nullable=true)
     */
    private $hInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="h_termino", type="time", nullable=true)
     */
    private $hTermino;

    /**
     * @var boolean
     *
     * @ORM\Column(name="commited", type="boolean", nullable=true)
     */
    private $commited;

    /**
     * @var integer
     *
     * @ORM\Column(name="jornada", type="integer", nullable=true)
     */
    private $jornada;

    /**
     * @var integer
     *
     * @ORM\Column(name="rama", type="integer", nullable=true)
     */
    private $rama;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoria", type="integer", nullable=true)
     */
    private $categoria;

    /**
     * @var integer
     *
     * @ORM\Column(name="numpar", type="integer", nullable=true)
     */
    private $numpar;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_partido", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPartido;

    /**
     * @var \limubac\administratorBundle\Entity\Estatus
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Estatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estatus", referencedColumnName="id_estatus")
     * })
     */
    private $idEstatus;

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
     * Set hInicio
     *
     * @param \DateTime $hInicio
     *
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
     *
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
     * Set commited
     *
     * @param boolean $commited
     *
     * @return Partido
     */
    public function setCommited($commited)
    {
        $this->commited = $commited;

        return $this;
    }

    /**
     * Get commited
     *
     * @return boolean
     */
    public function getCommited()
    {
        return $this->commited;
    }

    /**
     * Set jornada
     *
     * @param integer $jornada
     *
     * @return Partido
     */
    public function setJornada($jornada)
    {
        $this->jornada = $jornada;

        return $this;
    }

    /**
     * Get jornada
     *
     * @return integer
     */
    public function getJornada()
    {
        return $this->jornada;
    }

    /**
     * Set rama
     *
     * @param integer $rama
     *
     * @return Partido
     */
    public function setRama($rama)
    {
        $this->rama = $rama;

        return $this;
    }

    /**
     * Get rama
     *
     * @return integer
     */
    public function getRama()
    {
        return $this->rama;
    }

    /**
     * Set categoria
     *
     * @param integer $categoria
     *
     * @return Partido
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return integer
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set numpar
     *
     * @param integer $numpar
     *
     * @return Partido
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;

        return $this;
    }

    /**
     * Get numpar
     *
     * @return integer
     */
    public function getNumpar()
    {
        return $this->numpar;
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
     * Set idEstatus
     *
     * @param \limubac\administratorBundle\Entity\Estatus $idEstatus
     *
     * @return Partido
     */
    public function setIdEstatus(\limubac\administratorBundle\Entity\Estatus $idEstatus = null)
    {
        $this->idEstatus = $idEstatus;

        return $this;
    }

    /**
     * Get idEstatus
     *
     * @return \limubac\administratorBundle\Entity\Estatus
     */
    public function getIdEstatus()
    {
        return $this->idEstatus;
    }

    /**
     * Set idSede
     *
     * @param \limubac\administratorBundle\Entity\Sede $idSede
     *
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
     *
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
     *
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
}
