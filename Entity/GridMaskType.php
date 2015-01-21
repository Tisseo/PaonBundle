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
}
