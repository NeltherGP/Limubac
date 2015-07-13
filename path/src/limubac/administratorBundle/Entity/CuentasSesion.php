<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CuentasSesion
 *
 * @ORM\Table(name="cuentas_sesion", indexes={@ORM\Index(name="cuentas_sesion_ibfk_1", columns={"id_usuario"})})
 * @ORM\Entity
 */
class CuentasSesion
{
    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=255, nullable=false)
     */
    private $contrasena;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_sesion", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSesion;

    /**
     * @var \limubac\administratorBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="limubac\administratorBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id_usuario")
     * })
     */
    private $idUsuario;


}

