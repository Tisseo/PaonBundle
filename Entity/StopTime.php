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
     * @var \Tisseo\DatawarehouseBundle\Entity\RouteStop
     */
    private $routeStop;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Trip
     */
    private $trip;


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
     * Set routeStop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\RouteStop $routeStop
     * @return StopTime
     */
    public function setRouteStop(\Tisseo\DatawarehouseBundle\Entity\RouteStop $routeStop = null)
    {
        $this->routeStop = $routeStop;

        return $this;
    }

    /**
     * Get routeStop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\RouteStop 
     */
    public function getRouteStop()
    {
        return $this->routeStop;
    }

    /**
     * Set trip
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Trip $trip
     * @return StopTime
     */
    public function setTrip(\Tisseo\DatawarehouseBundle\Entity\Trip $trip = null)
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
}
