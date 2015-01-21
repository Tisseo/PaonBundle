<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineVersionNotExported
 */
class LineVersionNotExported
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
     * Set exportDestination
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\ExportDestination $exportDestination
     * @return LineVersionNotExported
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
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return LineVersionNotExported
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
