<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoSanguineo
 *
 * @ORM\Table(name="tipo_sanguineo", indexes={@ORM\Index(name="id_tiposanguineo", columns={"id_tiposanguineo"})})
 * @ORM\Entity
 */
class TipoSanguineo
{
    /**
     * @var string
     *
     * @ORM\Column(name="tipo_sangre", type="string", length=5, nullable=false)
     */
    private $tipoSangre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tiposanguineo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTiposanguineo;



    /**
     * Set tipoSangre
     *
     * @param string $tipoSangre
     *
     * @return TipoSanguineo
     */
    public function setTipoSangre($tipoSangre)
    {
        $this->tipoSangre = $tipoSangre;

        return $this;
    }

    /**
     * Get tipoSangre
     *
     * @return string
     */
    public function getTipoSangre()
    {
        return $this->tipoSangre;
    }

    /**
     * Get idTiposanguineo
     *
     * @return integer
     */
    public function getIdTiposanguineo()
    {
        return $this->idTiposanguineo;
    }
}
