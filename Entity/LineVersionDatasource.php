<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineVersionDatasource
 */
class LineVersionDatasource
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
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $lineVersion;


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
     * @return LineVersionDatasource
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
     * @return LineVersionDatasource
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
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return LineVersionDatasource
     */
    public function setLineVersion(\Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion = null)
    {
        $this->lineVersion = $lineVersion;

        return $this;
    }

    /**
     * Get lineVersion
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\LineVersion 
     */
    public function getLineVersion()
    {
        return $this->lineVersion;
    }
}
