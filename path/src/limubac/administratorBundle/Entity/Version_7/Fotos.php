<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fotos
 *
 * @ORM\Table(name="fotos")
 * @ORM\Entity
 */
class Fotos
{
    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="blob", nullable=false)
     */
    private $foto;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_foto", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFoto;



    /**
     * Set foto
     *
     * @param string $foto
     * @return Fotos
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Get idFoto
     *
     * @return integer 
     */
    public function getIdFoto()
    {
        return $this->idFoto;
    }
}
