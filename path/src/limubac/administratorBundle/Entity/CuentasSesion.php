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



    /**
     * Set contrasena
     *
     * @param string $contrasena
     * @return CuentasSesion
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string 
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Get idSesion
     *
     * @return integer 
     */
    public function getIdSesion()
    {
        return $this->idSesion;
    }

    /**
     * Set idUsuario
     *
     * @param \limubac\administratorBundle\Entity\Usuario $idUsuario
     * @return CuentasSesion
     */
    public function setIdUsuario(\limubac\administratorBundle\Entity\Usuario $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \limubac\administratorBundle\Entity\Usuario 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
}
