<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finanzas
 *
 * @ORM\Table(name="finanzas", indexes={@ORM\Index(name="finanzas_ibfk_1", columns={"id_torneo"}), @ORM\Index(name="finanzas_ibfk_2", columns={"id_equipo"})})
 * @ORM\Entity
 */
class Finanzas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="inscripcion", type="integer", nullable=false)
     */
    private $inscripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="h_dia", type="integer", nullable=false)
     */
    private $hDia;

    /**
     * @var string
     *
     * @ORM\Column(name="hora", type="string", length=11, nullable=false)
     */
    private $hora;

    /**
     * @var integer
     *
     * @ORM\Column(name="monto", type="integer", nullable=false)
     */
    private $monto;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuenta", type="integer", nullable=false)
     */
    private $cuenta;

    /**
     * @var string
     *
     * @ORM\Column(name="manejo", type="string", length=255, nullable=false)
     */
    private $manejo;

    /**
     * @var integer
     *
     * @ORM\Column(name="enero", type="integer", nullable=false)
     */
    private $enero;

    /**
     * @var integer
     *
     * @ORM\Column(name="febrero", type="integer", nullable=false)
     */
    private $febrero;

    /**
     * @var integer
     *
     * @ORM\Column(name="marzo", type="integer", nullable=false)
     */
    private $marzo;

    /**
     * @var integer
     *
     * @ORM\Column(name="abril", type="integer", nullable=false)
     */
    private $abril;

    /**
     * @var integer
     *
     * @ORM\Column(name="mayo", type="integer", nullable=false)
     */
    private $mayo;

    /**
     * @var integer
     *
     * @ORM\Column(name="junio", type="integer", nullable=false)
     */
    private $junio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_finanzas", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFinanzas;

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
     * @var \limubac\administratorBundle\Entity\Torneo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Torneo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_torneo", referencedColumnName="id_torneo")
     * })
     */
    private $idTorneo;



    /**
     * Set inscripcion
     *
     * @param integer $inscripcion
     * @return Finanzas
     */
    public function setInscripcion($inscripcion)
    {
        $this->inscripcion = $inscripcion;

        return $this;
    }

    /**
     * Get inscripcion
     *
     * @return integer 
     */
    public function getInscripcion()
    {
        return $this->inscripcion;
    }

    /**
     * Set hDia
     *
     * @param integer $hDia
     * @return Finanzas
     */
    public function setHDia($hDia)
    {
        $this->hDia = $hDia;

        return $this;
    }

    /**
     * Get hDia
     *
     * @return integer 
     */
    public function getHDia()
    {
        return $this->hDia;
    }

    /**
     * Set hora
     *
     * @param string $hora
     * @return Finanzas
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return string 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set monto
     *
     * @param integer $monto
     * @return Finanzas
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return integer 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set cuenta
     *
     * @param integer $cuenta
     * @return Finanzas
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return integer 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set manejo
     *
     * @param string $manejo
     * @return Finanzas
     */
    public function setManejo($manejo)
    {
        $this->manejo = $manejo;

        return $this;
    }

    /**
     * Get manejo
     *
     * @return string 
     */
    public function getManejo()
    {
        return $this->manejo;
    }

    /**
     * Set enero
     *
     * @param integer $enero
     * @return Finanzas
     */
    public function setEnero($enero)
    {
        $this->enero = $enero;

        return $this;
    }

    /**
     * Get enero
     *
     * @return integer 
     */
    public function getEnero()
    {
        return $this->enero;
    }

    /**
     * Set febrero
     *
     * @param integer $febrero
     * @return Finanzas
     */
    public function setFebrero($febrero)
    {
        $this->febrero = $febrero;

        return $this;
    }

    /**
     * Get febrero
     *
     * @return integer 
     */
    public function getFebrero()
    {
        return $this->febrero;
    }

    /**
     * Set marzo
     *
     * @param integer $marzo
     * @return Finanzas
     */
    public function setMarzo($marzo)
    {
        $this->marzo = $marzo;

        return $this;
    }

    /**
     * Get marzo
     *
     * @return integer 
     */
    public function getMarzo()
    {
        return $this->marzo;
    }

    /**
     * Set abril
     *
     * @param integer $abril
     * @return Finanzas
     */
    public function setAbril($abril)
    {
        $this->abril = $abril;

        return $this;
    }

    /**
     * Get abril
     *
     * @return integer 
     */
    public function getAbril()
    {
        return $this->abril;
    }

    /**
     * Set mayo
     *
     * @param integer $mayo
     * @return Finanzas
     */
    public function setMayo($mayo)
    {
        $this->mayo = $mayo;

        return $this;
    }

    /**
     * Get mayo
     *
     * @return integer 
     */
    public function getMayo()
    {
        return $this->mayo;
    }

    /**
     * Set junio
     *
     * @param integer $junio
     * @return Finanzas
     */
    public function setJunio($junio)
    {
        $this->junio = $junio;

        return $this;
    }

    /**
     * Get junio
     *
     * @return integer 
     */
    public function getJunio()
    {
        return $this->junio;
    }

    /**
     * Get idFinanzas
     *
     * @return integer 
     */
    public function getIdFinanzas()
    {
        return $this->idFinanzas;
    }

    /**
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     * @return Finanzas
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
     * Set idTorneo
     *
     * @param \limubac\administratorBundle\Entity\Torneo $idTorneo
     * @return Finanzas
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
