<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteNotExported
 */
class RouteNotExported
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
     * @var \Tisseo\DatawarehouseBundle\Entity\Route
     */
    private $route;


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
     * @return RouteNotExported
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
     * Set route
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Route $route
     * @return RouteNotExported
     */
    public function setRoute(\Tisseo\DatawarehouseBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }
}
