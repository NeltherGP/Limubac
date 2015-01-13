<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * participan_T
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\participan_TRepository")
 */
class participan_T
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
     * @ORM\Column(name="id_torneo", type="integer")
     */
    private $idTorneo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_equipo", type="integer")
     */
    private $idEquipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_categoria", type="integer")
     */
    private $idCategoria;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_rama", type="integer")
     */
    private $idRama;


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
     * Set idTorneo
     *
     * @param integer $idTorneo
     * @return participan_T
     */
    public function setIdTorneo($idTorneo)
    {
        $this->idTorneo = $idTorneo;

        return $this;
    }

    /**
     * Get idTorneo
     *
     * @return integer 
     */
    public function getIdTorneo()
    {
        return $this->idTorneo;
    }

    /**
     * Set idEquipo
     *
     * @param integer $idEquipo
     * @return participan_T
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
     * Set idCategoria
     *
     * @param integer $idCategoria
     * @return participan_T
     */
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return integer 
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set idRama
     *
     * @param integer $idRama
     * @return participan_T
     */
    public function setIdRama($idRama)
    {
        $this->idRama = $idRama;

        return $this;
    }

    /**
     * Get idRama
     *
     * @return integer 
     */
    public function getIdRama()
    {
        return $this->idRama;
    }
}
