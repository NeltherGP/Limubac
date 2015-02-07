<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity
 */
class Categoria
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=35, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="edad", type="string", length=40, nullable=false)
     */
    private $edad;

    /**
     * @var integer
     *
     * @ORM\Column(name="limite_equipo", type="integer", nullable=false)
     */
    private $limiteEquipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_categoria", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategoria;



    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Categoria
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
     * Set edad
     *
     * @param string $edad
     * @return Categoria
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad
     *
     * @return string 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set limiteEquipo
     *
     * @param integer $limiteEquipo
     * @return Categoria
     */
    public function setLimiteEquipo($limiteEquipo)
    {
        $this->limiteEquipo = $limiteEquipo;

        return $this;
    }

    /**
     * Get limiteEquipo
     *
     * @return integer 
     */
    public function getLimiteEquipo()
    {
        return $this->limiteEquipo;
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
}
