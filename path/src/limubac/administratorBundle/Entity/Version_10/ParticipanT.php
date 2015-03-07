<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParticipanT
 *
 * @ORM\Table(name="participan_t", indexes={@ORM\Index(name="id_torneo", columns={"id_torneo", "id_equipo", "id_categoria", "id_rama"}), @ORM\Index(name="id_equipo", columns={"id_equipo"}), @ORM\Index(name="id_categoria", columns={"id_categoria"}), @ORM\Index(name="id_rama", columns={"id_rama"}), @ORM\Index(name="IDX_997D18585ADCD613", columns={"id_torneo"})})
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
     * @var \limubac\administratorBundle\Entity\RamaEquipo
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\RamaEquipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_rama", referencedColumnName="id_rama")
     * })
     */
    private $idRama;

    /**
     * @var \limubac\administratorBundle\Entity\Categoria
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Categoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categoria", referencedColumnName="id_categoria")
     * })
     */
    private $idCategoria;

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
     * Set idRama
     *
     * @param \limubac\administratorBundle\Entity\RamaEquipo $idRama
     * @return ParticipanT
     */
    public function setIdRama(\limubac\administratorBundle\Entity\RamaEquipo $idRama = null)
    {
        $this->idRama = $idRama;

        return $this;
    }

    /**
     * Get idRama
     *
     * @return \limubac\administratorBundle\Entity\RamaEquipo 
     */
    public function getIdRama()
    {
        return $this->idRama;
    }

    /**
     * Set idCategoria
     *
     * @param \limubac\administratorBundle\Entity\Categoria $idCategoria
     * @return ParticipanT
     */
    public function setIdCategoria(\limubac\administratorBundle\Entity\Categoria $idCategoria = null)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return \limubac\administratorBundle\Entity\Categoria 
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set idEquipo
     *
     * @param \limubac\administratorBundle\Entity\Equipo $idEquipo
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
