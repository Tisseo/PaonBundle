<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Calendar
 */
class Calendar
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
    private $calendarType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $trips;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarElements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarDatasources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->calendarElements = new ArrayCollection();
        $this->calendarDatasources = new ArrayCollection();
        $this->trips = new ArrayCollection();
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
     * @return Calendar
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
     * Set calendarType
     *
     * @param string $calendarType
     * @return Calendar
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
     * Add calendarElements
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements
     * @return Calendar
     */
    public function addCalendarElement(CalendarElement $calendarElements)
    {
        $this->calendarElements[] = $calendarElements;

        return $this;
    }

    /**
     * Remove calendarElements
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements
     */
    public function removeCalendarElement(CalendarElement $calendarElements)
    {
        $this->calendarElements->removeElement($calendarElements);
    }

    /**
     * Get calendarElements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarElements()
    {
        return $this->calendarElements;
    }

    /**
     * Add calendarDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources
     * @return Calendar
     */
    public function addCalendarDatasource(CalendarDatasource $calendarDatasources)
    {
        $this->calendarDatasources[] = $calendarDatasources;

        return $this;
    }

    /**
     * Remove calendarDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources
     */
    public function removeCalendarDatasource(CalendarDatasource $calendarDatasources)
    {
        $this->calendarDatasources->removeElement($calendarDatasources);
    }

    /**
     * Get calendarDatasources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarDatasources()
    {
        return $this->calendarDatasources;
    }
}
