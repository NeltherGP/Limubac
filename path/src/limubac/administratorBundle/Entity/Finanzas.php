<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finanzas
 *
 * @ORM\Table(name="finanzas", indexes={@ORM\Index(name="id_finanzas", columns={"id_finanzas"}), @ORM\Index(name="finanzas_ibfk_1", columns={"id_torneo"}), @ORM\Index(name="finanzas_ibfk_2", columns={"id_equipo"})})
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
     * @var string
     *
     * @ORM\Column(name="dia", type="string", length=255, nullable=false)
     */
    private $dia;

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
     * @ORM\Column(name="mes_1", type="integer", nullable=false)
     */
    private $mes1;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_2", type="integer", nullable=false)
     */
    private $mes2;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_3", type="integer", nullable=false)
     */
    private $mes3;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_4", type="integer", nullable=false)
     */
    private $mes4;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_5", type="integer", nullable=false)
     */
    private $mes5;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_6", type="integer", nullable=false)
     */
    private $mes6;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes_7", type="integer", nullable=false)
     */
    private $mes7;

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
     *
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
     * Set dia
     *
     * @param string $dia
     *
     * @return Finanzas
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return string
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set hora
     *
     * @param string $hora
     *
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
     *
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
     *
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
     *
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
     * Set mes1
     *
     * @param integer $mes1
     *
     * @return Finanzas
     */
    public function setMes1($mes1)
    {
        $this->mes1 = $mes1;

        return $this;
    }

    /**
     * Get mes1
     *
     * @return integer
     */
    public function getMes1()
    {
        return $this->mes1;
    }

    /**
     * Set mes2
     *
     * @param integer $mes2
     *
     * @return Finanzas
     */
    public function setMes2($mes2)
    {
        $this->mes2 = $mes2;

        return $this;
    }

    /**
     * Get mes2
     *
     * @return integer
     */
    public function getMes2()
    {
        return $this->mes2;
    }

    /**
     * Set mes3
     *
     * @param integer $mes3
     *
     * @return Finanzas
     */
    public function setMes3($mes3)
    {
        $this->mes3 = $mes3;

        return $this;
    }

    /**
     * Get mes3
     *
     * @return integer
     */
    public function getMes3()
    {
        return $this->mes3;
    }

    /**
     * Set mes4
     *
     * @param integer $mes4
     *
     * @return Finanzas
     */
    public function setMes4($mes4)
    {
        $this->mes4 = $mes4;

        return $this;
    }

    /**
     * Get mes4
     *
     * @return integer
     */
    public function getMes4()
    {
        return $this->mes4;
    }

    /**
     * Set mes5
     *
     * @param integer $mes5
     *
     * @return Finanzas
     */
    public function setMes5($mes5)
    {
        $this->mes5 = $mes5;

        return $this;
    }

    /**
     * Get mes5
     *
     * @return integer
     */
    public function getMes5()
    {
        return $this->mes5;
    }

    /**
     * Set mes6
     *
     * @param integer $mes6
     *
     * @return Finanzas
     */
    public function setMes6($mes6)
    {
        $this->mes6 = $mes6;

        return $this;
    }

    /**
     * Get mes6
     *
     * @return integer
     */
    public function getMes6()
    {
        return $this->mes6;
    }

    /**
     * Set mes7
     *
     * @param integer $mes7
     *
     * @return Finanzas
     */
    public function setMes7($mes7)
    {
        $this->mes7 = $mes7;

        return $this;
    }

    /**
     * Get mes7
     *
     * @return integer
     */
    public function getMes7()
    {
        return $this->mes7;
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
     *
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
     *
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
