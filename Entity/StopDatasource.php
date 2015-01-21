<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StopDatasource
 */
class StopDatasource
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
     * @var \Tisseo\DatawarehouseBundle\Entity\Stop
     */
    private $stop;


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
     * @return StopDatasource
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
     * @return StopDatasource
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
     * Set stop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Stop $stop
     * @return StopDatasource
     */
    public function setStop(\Tisseo\DatawarehouseBundle\Entity\Stop $stop = null)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Stop 
     */
    public function getStop()
    {
        return $this->stop;
    }
}
