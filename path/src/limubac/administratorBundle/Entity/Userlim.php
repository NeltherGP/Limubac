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


}

