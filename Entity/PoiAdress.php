<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoiAdress
 */
class PoiAdress
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $adress;

    /**
     * @var boolean
     */
    private $isEntrance;

    /**
     * @var geometry
     */
    private $theGeom;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Poi
     */
    private $poi;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set adress
     *
     * @param string $adress
     * @return PoiAdress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set isEntrance
     *
     * @param boolean $isEntrance
     * @return PoiAdress
     */
    public function setIsEntrance($isEntrance)
    {
        $this->isEntrance = $isEntrance;

        return $this;
    }

    /**
     * Get isEntrance
     *
     * @return boolean 
     */
    public function getIsEntrance()
    {
        return $this->isEntrance;
    }

    /**
     * Set theGeom
     *
     * @param geometry $theGeom
     * @return PoiAdress
     */
    public function setTheGeom($theGeom)
    {
        $this->theGeom = $theGeom;

        return $this;
    }

    /**
     * Get theGeom
     *
     * @return geometry 
     */
    public function getTheGeom()
    {
        return $this->theGeom;
    }

    /**
     * Set poi
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Poi $poi
     * @return PoiAdress
     */
    public function setPoi(\Tisseo\DatawarehouseBundle\Entity\Poi $poi = null)
    {
        $this->poi = $poi;

        return $this;
    }

    /**
     * Get poi
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Poi 
     */
    public function getPoi()
    {
        return $this->poi;
    }
}
