<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RamaEquipo
 *
 * @ORM\Table(name="rama_equipo", indexes={@ORM\Index(name="id_rama", columns={"id_rama"})})
 * @ORM\Entity
 */
class RamaEquipo
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=35, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_rama", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRama;



    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RamaEquipo
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
     * Get idRama
     *
     * @return integer
     */
    public function getIdRama()
    {
        return $this->idRama;
    }
}
