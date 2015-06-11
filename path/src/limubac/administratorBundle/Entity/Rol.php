<?php

namespace limubac\administratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity
 */
class Rol
{
    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=255, nullable=false)
     */
    private $rol;

    /**
     * @var integer
     *
     * @ORM\Column(name="permisos", type="integer", nullable=false)
     */
    private $permisos;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_rol", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRol;



    /**
     * Set rol
     *
     * @param string $rol
     * @return Rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set permisos
     *
     * @param integer $permisos
     * @return Rol
     */
    public function setPermisos($permisos)
    {
        $this->permisos = $permisos;

        return $this;
    }

    /**
     * Get permisos
     *
     * @return integer 
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * Get idRol
     *
     * @return integer 
     */
    public function getIdRol()
    {
        return $this->idRol;
    }
}
