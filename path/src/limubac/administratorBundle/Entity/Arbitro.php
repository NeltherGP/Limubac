<?php

namespace limubac\administratorBundle\Entity;

    
use Doctrine\ORM\Mapping as ORM;

/**
 * Arbitro
 *
 * @ORM\Table(name="arbitro")
 * @ORM\Entity
 */
class Arbitro
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_arbitro", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArbitro;



    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Arbitro
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
     * Get idArbitro
     *
     * @return integer 
     */
    public function getIdArbitro()
    {
        return $this->idArbitro;
    }
}
