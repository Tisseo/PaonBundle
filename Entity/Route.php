<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Route
 */
class Route
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $way;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $direction;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Comment
     */
    private $comment;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $lineVersion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $trips;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getLineVersionId()
    {
        return $this->lineVersion->getId();
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
     * Set way
     *
     * @param string $way
     * @return Route
     */
    public function setWay($way)
    {
        $this->way = $way;

        return $this;
    }

    /**
     * Get way
     *
     * @return string 
     */
    public function getWay()
    {
        return $this->way;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Route
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
     * Set direction
     *
     * @param string $direction
     * @return Route
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string 
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set comment
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Comment $comment
     * @return Route
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
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return Route
     */
    public function setLineVersion(\Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion = null)
    {
        $this->lineVersion = $lineVersion;

        return $this;
    }

    /**
     * Get lineVersion
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\LineVersion 
     */
    public function getLineVersion()
    {
        return $this->lineVersion;
    }
}
