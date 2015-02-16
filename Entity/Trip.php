<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trip
 */
class Trip
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Comment
     */
    private $comment;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Route
     */
    private $route;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\TripCalendar
     */
    private $tripCalendar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tripDatasources;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $stopTimes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarLinks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->calendarLinks = new ArrayCollection();
        $this->tripDatasources = new ArrayCollection();
        $this->stopTimes = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Trip
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set comment
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Comment $comment
     * @return Trip
     */
    public function setComment(Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set route
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Route $route
     * @return Trip
     */
    public function setRoute(Route $route = null)
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
     * Set tripCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendar
     * @return Trip
     */
    public function setTripCalendar(TripCalendar $tripCalendar = null)
    {
        $this->tripCalendar = $tripCalendar;

        return $this;
    }

    /**
     * Get tripCalendar
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\TripCalendar 
     */
    public function getTripCalendar()
    {
        return $this->tripCalendar;
    }

    /**
     * Add calendarLinks
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinks
     * @return Trip
     */
    public function addCalendarLink(CalendarLink $calendarLinks)
    {
        $this->calendarLinks[] = $calendarLinks;

        return $this;
    }

    /**
     * Remove calendarLinks
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinks
     */
    public function removeCalendarLink(CalendarLink $calendarLinks)
    {
        $this->calendarLinks->removeElement($calendarLinks);
    }

    /**
     * Get calendarLinks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarLinks()
    {
        return $this->calendarLinks;
    }

    /**
     * Add stopTimes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\StopTime $stopTimes
     * @return Trip
     */
    public function addStopTime(StopTime $stopTimes)
    {
        $this->stopTimes[] = $stopTimes;

        return $this;
    }

    /**
     * Remove stopTimes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\StopTime $stopTimes
     */
    public function removeStopTime(StopTime $stopTimes)
    {
        $this->stopTimes->removeElement($stopTimes);
    }

    /**
     * Get stopTimes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStopTimes()
    {
        return $this->stopTimes;
    }

    /**
     * Add tripDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripDatasource $tripDatasources
     * @return Trip
     */
    public function addTripDatasource(TripDatasource $tripDatasources)
    {
        $this->tripDatasources[] = $tripDatasources;

        return $this;
    }

    /**
     * Remove tripDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripDatasource $tripDatasources
     */
    public function removeTripDatasource(TripDatasource $tripDatasources)
    {
        $this->tripDatasources->removeElement($tripDatasources);
    }

    /**
     * Get tripDatasources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTripDatasources()
    {
        return $this->tripDatasources;
    }

    public function __toString()
    {
        $trip = "id: ".strval($this->id)."\n";
        $trip += "name: ".strval($this->name)."\n";
        $trip += "route: ".($this->route !== null ? strval($this->route->getId()) : "None")."\n";
        $trip += "calendar: ".($this->tripCalendar !== null ? strval($this->tripCalendar->getId()) : "None")."\n";
        $trip += "comment: ".($this->comment !== null ? strval($this->comment->getId()) : "None")."\n";
        $trip += "---- datasources ----\n";
        foreach($this->tripDatasources as $datasource) {
            $trip += "\t".strval($datasource->getId())." / ".strval($datasource->getCode())."\n";
        }
        $trip += "---- stoptimes ----\n";
        foreach($this->stopTimes as $stopTime) {
            $trip += "\t".strval($stopTime->getId())."\n";
        }
        $trip += "---- calendarlinks ----\n";
        foreach($this->calendarLinks as $calendarLink) {
            $trip += "\t".strval($calendarLink->getId())." / ".strval($calendarLink->getPeriodCalendar()->getId())."\n";
        }

        return strval($trip);
    }
}
