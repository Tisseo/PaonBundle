<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GridMaskType
 */
class GridMaskType
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
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $calendarType;

    /**
     * @var string
     */
    private $calendarPeriod;

    public function getRelatedTripCalendars($lineVersionId)
    {
        $tempCalendars = array();
        foreach ($this->tripCalendars as $tripCalendar)
        {
            foreach ($tripCalendar->getTrips() as $trip)
            {
                if ($trip->getLineVersionId() == $lineVersionId)
                    $tempCalendars[] = $tripCalendar;
            }
        }
        $tripCalendars = array_unique($tempCalendars);

        return $tripCalendars;
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
     * @return GridMaskType
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
     * Set color
     *
     * @param string $color
     * @return GridMaskType
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set calendarType
     *
     * @param string $calendarType
     * @return GridMaskType
     */
    public function setCalendarType($calendarType)
    {
        $this->calendarType = $calendarType;

        return $this;
    }

    /**
     * Get calendarType
     *
     * @return string 
     */
    public function getCalendarType()
    {
        return $this->calendarType;
    }

    /**
     * Set calendarPeriod
     *
     * @param string $calendarPeriod
     * @return GridMaskType
     */
    public function setCalendarPeriod($calendarPeriod)
    {
        $this->calendarPeriod = $calendarPeriod;

        return $this;
    }

    /**
     * Get calendarPeriod
     *
     * @return string 
     */
    public function getCalendarPeriod()
    {
        return $this->calendarPeriod;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $gridLinkCalendarMaskTypes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tripCalendars;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gridLinkCalendarMaskTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tripCalendars = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gridLinkCalendarMaskTypes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes
     * @return GridMaskType
     */
    public function addGridLinkCalendarMaskType(\Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes)
    {
        $this->gridLinkCalendarMaskTypes[] = $gridLinkCalendarMaskTypes;

        return $this;
    }

    /**
     * Remove gridLinkCalendarMaskTypes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes
     */
    public function removeGridLinkCalendarMaskType(\Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes)
    {
        $this->gridLinkCalendarMaskTypes->removeElement($gridLinkCalendarMaskTypes);
    }

    /**
     * Get gridLinkCalendarMaskTypes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGridLinkCalendarMaskTypes()
    {
        return $this->gridLinkCalendarMaskTypes;
    }

    /**
     * Add tripCalendars
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendars
     * @return GridMaskType
     */
    public function addTripCalendar(\Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendars)
    {
        $this->tripCalendars[] = $tripCalendars;

        return $this;
    }

    /**
     * Remove tripCalendars
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendars
     */
    public function removeTripCalendar(\Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendars)
    {
        $this->tripCalendars->removeElement($tripCalendars);
    }

    /**
     * Get tripCalendars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTripCalendars()
    {
        return $this->tripCalendars;
    }
}
