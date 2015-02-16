<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     */
    private $calendarType;


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
     * @param integer $calendarType
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
     * @return integer 
     */
    public function getCalendarType()
    {
        return $this->calendarType;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarLinksPeriod;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarLinksDay;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarElements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->calendarLinksPeriod = new \Doctrine\Common\Collections\ArrayCollection();
        $this->calendarLinksDay = new \Doctrine\Common\Collections\ArrayCollection();
        $this->calendarElements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add calendarLinksPeriod
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksPeriod
     * @return Calendar
     */
    public function addCalendarLinksPeriod(\Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksPeriod)
    {
        $this->calendarLinksPeriod[] = $calendarLinksPeriod;

        return $this;
    }

    /**
     * Remove calendarLinksPeriod
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksPeriod
     */
    public function removeCalendarLinksPeriod(\Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksPeriod)
    {
        $this->calendarLinksPeriod->removeElement($calendarLinksPeriod);
    }

    /**
     * Get calendarLinksPeriod
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarLinksPeriod()
    {
        return $this->calendarLinksPeriod;
    }

    /**
     * Add calendarLinksDay
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksDay
     * @return Calendar
     */
    public function addCalendarLinksDay(\Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksDay)
    {
        $this->calendarLinksDay[] = $calendarLinksDay;

        return $this;
    }

    /**
     * Remove calendarLinksDay
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksDay
     */
    public function removeCalendarLinksDay(\Tisseo\DatawarehouseBundle\Entity\CalendarLink $calendarLinksDay)
    {
        $this->calendarLinksDay->removeElement($calendarLinksDay);
    }

    /**
     * Get calendarLinksDay
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarLinksDay()
    {
        return $this->calendarLinksDay;
    }

    /**
     * Add calendarElements
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements
     * @return Calendar
     */
    public function addCalendarElement(\Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements)
    {
        $this->calendarElements[] = $calendarElements;

        return $this;
    }

    /**
     * Remove calendarElements
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements
     */
    public function removeCalendarElement(\Tisseo\DatawarehouseBundle\Entity\CalendarElement $calendarElements)
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $calendarDatasources;


    /**
     * Add calendarDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources
     * @return Calendar
     */
    public function addCalendarDatasource(\Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources)
    {
        $this->calendarDatasources[] = $calendarDatasources;

        return $this;
    }

    /**
     * Remove calendarDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources
     */
    public function removeCalendarDatasource(\Tisseo\DatawarehouseBundle\Entity\CalendarDatasource $calendarDatasources)
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
