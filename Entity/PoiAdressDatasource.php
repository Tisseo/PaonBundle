<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoiAdressDatasource
 */
class PoiAdressDatasource
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Datasource
     */
    private $datasource;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\PoiAdress
     */
    private $poiAdress;


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
     * Set code
     *
     * @param string $code
     * @return PoiAdressDatasource
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set datasource
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Datasource $datasource
     * @return PoiAdressDatasource
     */
    public function setDatasource(\Tisseo\DatawarehouseBundle\Entity\Datasource $datasource = null)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * Get datasource
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Datasource 
     */
    public function getDatasource()
    {
        return $this->datasource;
    }

    /**
     * Set poiAdress
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\PoiAdress $poiAdress
     * @return PoiAdressDatasource
     */
    public function setPoiAdress(\Tisseo\DatawarehouseBundle\Entity\PoiAdress $poiAdress = null)
    {
        $this->poiAdress = $poiAdress;

        return $this;
    }

    /**
     * Get poiAdress
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\PoiAdress 
     */
    public function getPoiAdress()
    {
        return $this->poiAdress;
    }
}
