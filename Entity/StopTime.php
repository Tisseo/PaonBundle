<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StopTime
 */
class StopTime
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $arrivalTime;

    /**
     * @var integer
     */
    private $departureTime;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Trip
     */
    private $trip;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\RouteStop
     */
    private $route_stop;

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
     * Set arrivalTime
     *
     * @param integer $arrivalTime
     * @return StopTime
     */
    public function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    /**
     * Get arrivalTime
     *
     * @return integer 
     */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    /**
     * Set departureTime
     *
     * @param integer $departureTime
     * @return StopTime
     */
    public function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    /**
     * Get departureTime
     *
     * @return integer 
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Set trip
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Trip $trip
     * @return StopTime
     */
    public function setTrip(Trip $trip = null)
    {
        $this->trip = $trip;

        return $this;
    }

    /**
     * Get trip
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Trip 
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * Get trip
     *
     * @return StopTime 
     */
    public function removeTrip()
    {
        $this->trip = null;
        
        return $this;
    }

    /**
     * Set route_stop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\RouteStop $routeStop
     * @return StopTime
     */
    public function setRouteStop(RouteStop $routeStop = null)
    {
        $this->route_stop = $routeStop;

        return $this;
    }

    /**
     * Get route_stop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\RouteStop 
     */
    public function getRouteStop()
    {
        return $this->route_stop;
    }
}
