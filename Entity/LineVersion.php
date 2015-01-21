<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineVersion
 */
class LineVersion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $version;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \DateTime
     */
    private $plannedEndDate;

    /**
     * @var integer
     */
    private $childLineId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $forwardDirection;

    /**
     * @var string
     */
    private $backwardDirection;

    /**
     * @var string
     */
    private $bgColor;

    /**
     * @var string
     */
    private $bgHexaColor;

    /**
     * @var string
     */
    private $fgColor;

    /**
     * @var string
     */
    private $fgHexaColor;

    /**
     * @var string
     */
    private $cartoFile;

    /**
     * @var boolean
     */
    private $accessibility;

    /**
     * @var boolean
     */
    private $airConditioned;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $depot;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Line
     */
    private $line;


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
     * Set version
     *
     * @param integer $version
     * @return LineVersion
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return LineVersion
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return LineVersion
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set plannedEndDate
     *
     * @param \DateTime $plannedEndDate
     * @return LineVersion
     */
    public function setPlannedEndDate($plannedEndDate)
    {
        $this->plannedEndDate = $plannedEndDate;

        return $this;
    }

    /**
     * Get plannedEndDate
     *
     * @return \DateTime 
     */
    public function getPlannedEndDate()
    {
        return $this->plannedEndDate;
    }

    /**
     * Set childLineId
     *
     * @param integer $childLineId
     * @return LineVersion
     */
    public function setChildLineId($childLineId)
    {
        $this->childLineId = $childLineId;

        return $this;
    }

    /**
     * Get childLineId
     *
     * @return integer 
     */
    public function getChildLineId()
    {
        return $this->childLineId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return LineVersion
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
     * Set forwardDirection
     *
     * @param string $forwardDirection
     * @return LineVersion
     */
    public function setForwardDirection($forwardDirection)
    {
        $this->forwardDirection = $forwardDirection;

        return $this;
    }

    /**
     * Get forwardDirection
     *
     * @return string 
     */
    public function getForwardDirection()
    {
        return $this->forwardDirection;
    }

    /**
     * Set backwardDirection
     *
     * @param string $backwardDirection
     * @return LineVersion
     */
    public function setBackwardDirection($backwardDirection)
    {
        $this->backwardDirection = $backwardDirection;

        return $this;
    }

    /**
     * Get backwardDirection
     *
     * @return string 
     */
    public function getBackwardDirection()
    {
        return $this->backwardDirection;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     * @return LineVersion
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * Get bgColor
     *
     * @return string 
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set bgHexaColor
     *
     * @param string $bgHexaColor
     * @return LineVersion
     */
    public function setBgHexaColor($bgHexaColor)
    {
        $this->bgHexaColor = $bgHexaColor;

        return $this;
    }

    /**
     * Get bgHexaColor
     *
     * @return string 
     */
    public function getBgHexaColor()
    {
        return $this->bgHexaColor;
    }

    /**
     * Set fgColor
     *
     * @param string $fgColor
     * @return LineVersion
     */
    public function setFgColor($fgColor)
    {
        $this->fgColor = $fgColor;

        return $this;
    }

    /**
     * Get fgColor
     *
     * @return string 
     */
    public function getFgColor()
    {
        return $this->fgColor;
    }

    /**
     * Set fgHexaColor
     *
     * @param string $fgHexaColor
     * @return LineVersion
     */
    public function setFgHexaColor($fgHexaColor)
    {
        $this->fgHexaColor = $fgHexaColor;

        return $this;
    }

    /**
     * Get fgHexaColor
     *
     * @return string 
     */
    public function getFgHexaColor()
    {
        return $this->fgHexaColor;
    }

    /**
     * Set cartoFile
     *
     * @param string $cartoFile
     * @return LineVersion
     */
    public function setCartoFile($cartoFile)
    {
        $this->cartoFile = $cartoFile;

        return $this;
    }

    /**
     * Get cartoFile
     *
     * @return string 
     */
    public function getCartoFile()
    {
        return $this->cartoFile;
    }

    /**
     * Set accessibility
     *
     * @param boolean $accessibility
     * @return LineVersion
     */
    public function setAccessibility($accessibility)
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    /**
     * Get accessibility
     *
     * @return boolean 
     */
    public function getAccessibility()
    {
        return $this->accessibility;
    }

    /**
     * Set airConditioned
     *
     * @param boolean $airConditioned
     * @return LineVersion
     */
    public function setAirConditioned($airConditioned)
    {
        $this->airConditioned = $airConditioned;

        return $this;
    }

    /**
     * Get airConditioned
     *
     * @return boolean 
     */
    public function getAirConditioned()
    {
        return $this->airConditioned;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return LineVersion
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set depot
     *
     * @param string $depot
     * @return LineVersion
     */
    public function setDepot($depot)
    {
        $this->depot = $depot;

        return $this;
    }

    /**
     * Get depot
     *
     * @return string 
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * Set line
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Line $line
     * @return LineVersion
     */
    public function setLine(\Tisseo\DatawarehouseBundle\Entity\Line $line = null)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Get line
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Line 
     */
    public function getLine()
    {
        return $this->line;
    }
}
