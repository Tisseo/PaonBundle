<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalendarLink
 */
class CalendarLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Calendar
     */
    private $dayCalendar;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Calendar
     */
    private $periodCalendar;

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
     * Set dayCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Calendar $dayCalendar
     * @return CalendarLink
     */
    public function setDayCalendar(Calendar $dayCalendar = null)
    {
        $this->dayCalendar = $dayCalendar;

        return $this;
    }

    /**
     * Get dayCalendar
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Calendar 
     */
    public function getDayCalendar()
    {
        return $this->dayCalendar;
    }

    /**
     * Set periodCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Calendar $periodCalendar
     * @return CalendarLink
     */
    public function setPeriodCalendar(Calendar $periodCalendar = null)
    {
        $this->periodCalendar = $periodCalendar;

        return $this;
    }

    /**
     * Get periodCalendar
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Calendar 
     */
    public function getPeriodCalendar()
    {
        return $this->periodCalendar;
    }

    /**
     * Set trip
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Trip $trip
     * @return CalendarLink
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
}
