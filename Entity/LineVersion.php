<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @var boolean
     */
    private $certified;

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
     * @var \Tisseo\DatawarehouseBundle\Entity\Line
     */
    private $childLine;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $gridCalendars;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $printings;

    /**
     * @var integer
     */
    private $childLineId;

    /**
     * Constructor
     * @param LineVersion $previousLineVersion = null
     * @param Line $line = null
     *
     * Build a LineVersion with default values
     * Add information from $previousLineVersion if not null
     * Link to a specific Line if $line is not null
     */
    public function __construct(LineVersion $previousLineVersion = null, Line $line = null)
    {
        $this->gridCalendars = new ArrayCollection();
        $this->printings = new ArrayCollection();
        $this->startDate = new \Datetime();

        $this->version = 1;

        if ($previousLineVersion !== null)
        {
            if ($previousLineVersion->getEndDate() !== null)
            {
                $this->startDate = $previousLineVersion->getEndDate();
                $this->startDate->modify('+1 day');
            }
            $this->version = $previousLineVersion->getVersion() + 1;
            $this->name = $previousLineVersion->getName();
            $this->forwardDirection = $previousLineVersion->getForwardDirection();
            $this->backwardDirection = $previousLineVersion->getBackwardDirection();
            $this->fgColor = $previousLineVersion->getFgColor();
            $this->fgHexaColor = $previousLineVersion->getFgHexaColor();
            $this->bgColor = $previousLineVersion->getBgColor();
            $this->bgHexaColor = $previousLineVersion->getBgHexaColor();
            $this->accessibility = $previousLineVersion->getAccessibility();
            $this->airConditioned = $previousLineVersion->getAirConditioned();
            $this->certified = $previousLineVersion->getCertified();
            $this->depot = $previousLineVersion->getDepot();
            $this->setLine($previousLineVersion->getLine());
        }

        if ($line !== null)
        {
            $this->setLine($line);
        }
    }

    /**
     * mergeGridCalendars
     * @param LineVersion $lineVersion
     *
     * Attach gridCalendars passed from another LineVersion
     */
    public function mergeGridCalendars(LineVersion $lineVersion)
    {
        foreach($lineVersion->getGridCalendars() as $gridCalendar)
        {
            $newGridCalendar = new GridCalendar();
            $newGridCalendar->merge($gridCalendar, $this);
            $this->addGridCalendar($newGridCalendar);
        }
    }

    /**
     * closeDate
     * @param Datetime $date
     *
     * Set the endDate with the date passed as parameter
     */
    public function closeDate(\Datetime $date)
    {
        $this->endDate = new \Datetime($date->format('Y-m-d'));
        $this->endDate->modify('-1 day');
    }

    /**
     * isLocked
     * @return boolean
     *
     * A LineVersion is locked if :
     *  - it has started (i.e. startDate < now)
     *  - startDate is less than 20 days left
     */
    public function isLocked()
    {
        $now = new \Datetime();
        if ($this->startDate < $now)
            return true;
        else
        {
            $diff = intval($this->startDate->diff($now)->format('%a'));
            return ($diff < 20);
        }
    }

    /**
     * isNew
     *
     * @return boolean
     *
     * A LineVersion is new if no gridCalendars are linked to it
     */
    public function isNew()
    {
        return ($this->gridCalendars->isEmpty());
    }

    /**
     * isActive
     *
     * @return boolean
     *
     * A LineVersion is active if now is between its startDate/endDate
     */
    public function isActive()
    {
        $now = new \Datetime();
        return ($this->startDate < $now && ($this->endDate > $now || $this->endDate === null));
    }

    /**
     * getTotalPrintings
     *
     * @return integer
     *
     * Return the total amount of printings (i.e. printing.quantity)
     */
    public function getTotalPrintings()
    {
        $printings = 0;
        foreach($this->printings as $printing)
            $printings += $printing->getQuantity();

        return $printings;
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
     * Set childLine
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Line $childLine
     * @return LineVersion
     */
    public function setChildLine(Line $childLine = null)
    {
        $this->childLine = $childLine;

        return $this;
    }

    /**
     * Get childLine
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Line
     */
    public function getChildLine()
    {
        return $this->childLine;
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
     * Set certified
     *
     * @param boolean $certified
     * @return LineVersion
     */
    public function setCertified($certified)
    {
        $this->certified = $certified;

        return $this;
    }

    /**
     * Get certified
     *
     * @return boolean
     */
    public function getCertified()
    {
        return $this->certified;
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
    public function setLine(Line $line = null)
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

    /**
     * Add gridCalendars
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendars
     * @return Line
     */
    public function addGridCalendars(GridCalendar $gridCalendars)
    {
        $this->gridCalendars[] = $gridCalendars;
        $gridCalendars->setLineVersion($this);
        return $this;
    }

    /**
     * Set gridCalendars
     *
     * @param \Doctrine\Common\Collections\Collection $gridCalendars
     * @return Line
     */
    public function setGridCalendars(Collection $gridCalendars)
    {
        $this->gridCalendars = $gridCalendars;
        return $this;
    }

    /**
     * Remove gridCalendars 
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendars
     */
    public function removeGridCalendars(GridCalendar $gridCalendars)
    {
        $this->gridCalendars->removeElement($gridCalendars);
    }

    /**
     * Get gridCalendars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGridCalendars()
    {
        return $this->gridCalendars;
    }

    /**
     * Add gridCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendar
     * @return LineVersion
     */
    public function addGridCalendar(GridCalendar $gridCalendar)
    {
        $this->gridCalendars[] = $gridCalendar;

        return $this;
    }

    /**
     * Remove gridCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendar
     */
    public function removeGridCalendar(GridCalendar $gridCalendar)
    {
        $this->gridCalendars->removeElement($gridCalendar);
    }

    /**
     * Add printings
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Printing $printings
     * @return Line
     */
    public function addPrintings(Printing $printings)
    {
        $this->printings[] = $printings;
        $printings->setLineVersion($this);
        return $this;
    }

    /**
     * Set printings
     *
     * @param \Doctrine\Common\Collections\Collection $printings
     * @return Line
     */
    public function setPrintings(Collection $printings)
    {
        $this->printings = $printings;
        foreach ($this->printings as $gridCalendar) {
            $gridCalendar->setLineVersion($this);
        }
        return $this;
    }

    /**
     * Remove printings 
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Printing $printings
     */
    public function removePrintings(Printing $printings)
    {
        $this->printings->removeElement($printings);
    }

    /**
     * Get printings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrintings()
    {
        return $this->printings;
    }

    /**
     * Add printing
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Printing $printing
     * @return LineVersion
     */
    public function addPrinting(Printing $printing)
    {
        $this->printings[] = $printing;

        return $this;
    }

    /**
     * Remove printing
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Printing $printings
     */
    public function removePrinting(Printing $printing)
    {
        $this->printings->removeElement($printing);
    }

}
