<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteSection
 */
class RouteSection
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var geometry
     */
    private $theGeom;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Stop
     */
    private $endStop;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Stop
     */
    private $startStop;


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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return RouteSection
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return RouteSection
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set theGeom
     *
     * @param geometry $theGeom
     * @return RouteSection
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
     * Set endStop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Stop $endStop
     * @return RouteSection
     */
    public function setEndStop(\Tisseo\DatawarehouseBundle\Entity\Stop $endStop = null)
    {
        $this->endStop = $endStop;

        return $this;
    }

    /**
     * Get endStop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Stop 
     */
    public function getEndStop()
    {
        return $this->endStop;
    }

    /**
     * Set startStop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Stop $startStop
     * @return RouteSection
     */
    public function setStartStop(\Tisseo\DatawarehouseBundle\Entity\Stop $startStop = null)
    {
        $this->startStop = $startStop;

        return $this;
    }

    /**
     * Get startStop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Stop 
     */
    public function getStartStop()
    {
        return $this->startStop;
    }
}
