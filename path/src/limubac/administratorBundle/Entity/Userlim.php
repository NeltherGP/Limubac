<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userlim
 *
 * @ORM\Table(name="userlim")
 * @ORM\Entity
 */
class Userlim
{
    /**
     * @var string
     *
     * @ORM\Column(name="usuariolim", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $usuariolim;

    /**
     * @var string
     *
     * @ORM\Column(name="correolim", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $correolim;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasenalim", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $contrasenalim;



    /**
     * Set usuariolim
     *
     * @param string $usuariolim
     * @return Userlim
     */
    public function setUsuariolim($usuariolim)
    {
        $this->usuariolim = $usuariolim;

        return $this;
    }

    /**
     * Get usuariolim
     *
     * @return string 
     */
    public function getUsuariolim()
    {
        return $this->usuariolim;
    }

    /**
     * Set correolim
     *
     * @param string $correolim
     * @return Userlim
     */
    public function setCorreolim($correolim)
    {
        $this->correolim = $correolim;

        return $this;
    }

    /**
     * Get correolim
     *
     * @return string 
     */
    public function getCorreolim()
    {
        return $this->correolim;
    }

    /**
     * Set contrasenalim
     *
     * @param string $contrasenalim
     * @return Userlim
     */
    public function setContrasenalim($contrasenalim)
    {
        $this->contrasenalim = $contrasenalim;

        return $this;
    }

    /**
     * Get contrasenalim
     *
     * @return string 
     */
    public function getContrasenalim()
    {
        return $this->contrasenalim;
    }
}
