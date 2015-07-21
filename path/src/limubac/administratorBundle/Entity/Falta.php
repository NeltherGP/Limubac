<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Falta
 *
 * @ORM\Table(name="falta")
 * @ORM\Entity
 */
class Falta
{
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=35, nullable=false)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_falta", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFalta;



    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Falta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Get idFalta
     *
     * @return integer 
     */
    public function getIdFalta()
    {
        return $this->idFalta;
    }
}
