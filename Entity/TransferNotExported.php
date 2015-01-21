<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransferNotExported
 */
class TransferNotExported
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\ExportDestination
     */
    private $exportDestination;

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
     * Set exportDestination
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\ExportDestination $exportDestination
     * @return TransferNotExported
     */
    public function setExportDestination(\Tisseo\DatawarehouseBundle\Entity\ExportDestination $exportDestination = null)
    {
        $this->exportDestination = $exportDestination;

        return $this;
    }

    /**
     * Get exportDestination
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\ExportDestination 
     */
    public function getExportDestination()
    {
        return $this->exportDestination;
    }

    /**
     * Set transfer
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Transfer $transfer
     * @return TransferNotExported
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
