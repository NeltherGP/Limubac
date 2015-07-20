<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParticipanT
 *
 * @ORM\Table(name="participan_t", indexes={@ORM\Index(name="id_torneo", columns={"id_torneo", "id_equipo"}), @ORM\Index(name="id_equipo", columns={"id_equipo"}), @ORM\Index(name="IDX_997D18585ADCD613", columns={"id_torneo"})})
 * @ORM\Entity
 */
class ParticipanT
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_registro", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRegistro;

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
     * Get idRegistro
     *
     * @return integer
     */
    public function getIdRegistro()
    {
        return $this->idRegistro;
    }

    /**
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
     *
     * @return ParticipanT
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
     * @return ParticipanT
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
