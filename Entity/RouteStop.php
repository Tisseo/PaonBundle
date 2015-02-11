<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteStop
 */
class RouteStop
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $rank;

    /**
     * @var boolean
     */
    private $scheduledStop;

    /**
     * @var boolean
     */
    private $pickup;

    /**
     * @var boolean
     */
    private $dropOff;

    /**
     * @var boolean
     */
    private $reservationRequired;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Route
     */
    private $route;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\RouteSection
     */
    private $routeSection;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Waypoint
     */
    private $waypoint;

    /**
     * @var boolean
     */
    private $internalService;

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
     * Set rank
     *
     * @param integer $rank
     * @return RouteStop
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set scheduledStop
     *
     * @param boolean $scheduledStop
     * @return RouteStop
     */
    public function setScheduledStop($scheduledStop)
    {
        $this->scheduledStop = $scheduledStop;

        return $this;
    }

    /**
     * Get scheduledStop
     *
     * @return boolean 
     */
    public function getScheduledStop()
    {
        return $this->scheduledStop;
    }

    /**
     * Set pickup
     *
     * @param boolean $pickup
     * @return RouteStop
     */
    public function setPickup($pickup)
    {
        $this->pickup = $pickup;

        return $this;
    }

    /**
     * Get pickup
     *
     * @return boolean 
     */
    public function getPickup()
    {
        return $this->pickup;
    }

    /**
     * Set dropOff
     *
     * @param boolean $dropOff
     * @return RouteStop
     */
    public function setDropOff($dropOff)
    {
        $this->dropOff = $dropOff;

        return $this;
    }

    /**
     * Get dropOff
     *
     * @return boolean 
     */
    public function getDropOff()
    {
        return $this->dropOff;
    }

    /**
     * Set reservationRequired
     *
     * @param boolean $reservationRequired
     * @return RouteStop
     */
    public function setReservationRequired($reservationRequired)
    {
        $this->reservationRequired = $reservationRequired;

        return $this;
    }

    /**
     * Get reservationRequired
     *
     * @return boolean 
     */
    public function getReservationRequired()
    {
        return $this->reservationRequired;
    }

    /**
     * Set route
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Route $route
     * @return RouteStop
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

    /**
     * Set routeSection
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\RouteSection $routeSection
     * @return RouteStop
     */
    public function setRouteSection(\Tisseo\DatawarehouseBundle\Entity\RouteSection $routeSection = null)
    {
        $this->routeSection = $routeSection;

        return $this;
    }

    /**
     * Get routeSection
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\RouteSection 
     */
    public function getRouteSection()
    {
        return $this->routeSection;
    }

    /**
     * Set waypoint
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Waypoint $waypoint
     * @return RouteStop
     */
    public function setWaypoint(\Tisseo\DatawarehouseBundle\Entity\Waypoint $waypoint = null)
    {
        $this->waypoint = $waypoint;

        return $this;
    }

    /**
     * Get waypoint
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Waypoint 
     */
    public function getWaypoint()
    {
        return $this->waypoint;
    }

    /**
     * Set internalService
     *
     * @param boolean $internalService
     * @return RouteStop
     */
    public function setInternalService($internalService)
    {
        $this->internalService = $internalService;

        return $this;
    }

    /**
     * Get internalService
     *
     * @return boolean 
     */
    public function getInternalService()
    {
        return $this->internalService;
    }
}
