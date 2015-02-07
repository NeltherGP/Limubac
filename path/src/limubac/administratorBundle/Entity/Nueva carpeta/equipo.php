<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * equipo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="limubac\administratorBundle\Entity\equipoRepository")
 */
class equipo
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=35)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_capitan", type="integer")
     */
    private $idCapitan;

    /**
     * @var integer
     *
     * @ORM\Column(name="representante", type="integer")
     */
    private $representante;

    /**
     * @var integer
     *
     * @ORM\Column(name="auxiliar", type="integer")
     */
    private $auxiliar;


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
     * Set nombre
     *
     * @param string $nombre
     * @return equipo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set idCapitan
     *
     * @param integer $idCapitan
     * @return equipo
     */
    public function setIdCapitan($idCapitan)
    {
        $this->idCapitan = $idCapitan;

        return $this;
    }

    /**
     * Get idCapitan
     *
     * @return integer 
     */
    public function getIdCapitan()
    {
        return $this->idCapitan;
    }

    /**
     * Set representante
     *
     * @param integer $representante
     * @return equipo
     */
    public function setRepresentante($representante)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return integer 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set auxiliar
     *
     * @param integer $auxiliar
     * @return equipo
     */
    public function setAuxiliar($auxiliar)
    {
        $this->auxiliar = $auxiliar;

        return $this;
    }

    /**
     * Get auxiliar
     *
     * @return integer 
     */
    public function getAuxiliar()
    {
        return $this->auxiliar;
    }
}
