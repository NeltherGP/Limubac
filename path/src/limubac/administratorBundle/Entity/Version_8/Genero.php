<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genero
 *
 * @ORM\Table(name="genero")
 * @ORM\Entity
 */
class Genero
{
    /**
     * @var string
     *
     * @ORM\Column(name="generos", type="string", length=5, nullable=false)
     */
    private $generos;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_genero", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGenero;



    /**
     * Set generos
     *
     * @param string $generos
     * @return Genero
     */
    public function setGeneros($generos)
    {
        $this->generos = $generos;

        return $this;
    }

    /**
     * Get generos
     *
     * @return string 
     */
    public function getGeneros()
    {
        return $this->generos;
    }

    /**
     * Get idGenero
     *
     * @return integer 
     */
    public function getIdGenero()
    {
        return $this->idGenero;
    }
}
