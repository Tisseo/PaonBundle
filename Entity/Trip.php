<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    public function setComment(\Tisseo\DatawarehouseBundle\Entity\Comment $comment = null)
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
     * Set tripCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendar
     * @return Trip
     */
    public function setTripCalendar(\Tisseo\DatawarehouseBundle\Entity\TripCalendar $tripCalendar = null)
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
}
