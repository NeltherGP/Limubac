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
     * @var integer
     *
     * @ORM\Column(name="contrasenalim", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $contrasenalim;

    /**
     * @var integer
     *
     * @ORM\Column(name="correolim", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $correolim;



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
     * Set contrasenalim
     *
     * @param integer $contrasenalim
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
     * @return integer 
     */
    public function getContrasenalim()
    {
        return $this->contrasenalim;
    }

    /**
     * Set correolim
     *
     * @param integer $correolim
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
     * @return integer 
     */
    public function getCorreolim()
    {
        return $this->correolim;
    }
}
