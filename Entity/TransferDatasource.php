<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransferDatasource
 */
class TransferDatasource
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
     * @var \Tisseo\DatawarehouseBundle\Entity\Transfer
     */
    private $transfer;


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
     * @return TransferDatasource
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
     * @return TransferDatasource
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
     * Set transfer
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Transfer $transfer
     * @return TransferDatasource
     */
    public function setTransfer(\Tisseo\DatawarehouseBundle\Entity\Transfer $transfer = null)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * Get transfer
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Transfer 
     */
    public function getTransfer()
    {
        return $this->transfer;
    }
}
