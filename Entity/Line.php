<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Line
 */
class Line
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\PhysicalMode
     */
    private $physicalMode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $priorityLine;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->priorityLine = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set number
     *
     * @param string $number
     * @return Line
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set physicalMode
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\PhysicalMode $physicalMode
     * @return Line
     */
    public function setPhysicalMode(\Tisseo\DatawarehouseBundle\Entity\PhysicalMode $physicalMode = null)
    {
        $this->physicalMode = $physicalMode;

        return $this;
    }

    /**
     * Get physicalMode
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\PhysicalMode 
     */
    public function getPhysicalMode()
    {
        return $this->physicalMode;
    }

    /**
     * Add priorityLine
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Line $priorityLine
     * @return Line
     */
    public function addPriorityLine(\Tisseo\DatawarehouseBundle\Entity\Line $priorityLine)
    {
        $this->priorityLine[] = $priorityLine;

        return $this;
    }

    /**
     * Remove priorityLine
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Line $priorityLine
     */
    public function removePriorityLine(\Tisseo\DatawarehouseBundle\Entity\Line $priorityLine)
    {
        $this->priorityLine->removeElement($priorityLine);
    }

    /**
     * Get priorityLine
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPriorityLine()
    {
        return $this->priorityLine;
    }
}
