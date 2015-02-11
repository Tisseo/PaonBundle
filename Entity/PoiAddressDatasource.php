<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoiAddressDatasource
 */
class PoiAddressDatasource
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
     * @var \Tisseo\DatawarehouseBundle\Entity\PoiAddress
     */
    private $poiAddress;


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
     * @return PoiAddressDatasource
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
     * @return PoiAddressDatasource
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
     * Set poiAddress
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\PoiAddress $poiAddress
     * @return PoiAddressDatasource
     */
    public function setPoiAddress(\Tisseo\DatawarehouseBundle\Entity\PoiAddress $poiAddress = null)
    {
        $this->poiAddress = $poiAddress;

        return $this;
    }

    /**
     * Get poiAddress
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\PoiAddress 
     */
    public function getPoiAddress()
    {
        return $this->poiAddress;
    }
}
