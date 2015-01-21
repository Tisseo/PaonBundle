<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OdtStop
 */
class OdtStop
{
    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var boolean
     */
    private $pickup;

    /**
     * @var boolean
     */
    private $dropOff;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\OdtArea
     */
    private $odtArea;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Stop
     */
    private $stop;


    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return OdtStop
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
     * @return OdtStop
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
     * Set pickup
     *
     * @param boolean $pickup
     * @return OdtStop
     */
    public function setPickup($pickup)
    {
        $this->pickup = $pickup;

        return $this;
    }

    /**
     * Get pickup
     *
     * @return boolean 
     */
    public function getPickup()
    {
        return $this->pickup;
    }

    /**
     * Set dropOff
     *
     * @param boolean $dropOff
     * @return OdtStop
     */
    public function setDropOff($dropOff)
    {
        $this->dropOff = $dropOff;

        return $this;
    }

    /**
     * Get dropOff
     *
     * @return boolean 
     */
    public function getDropOff()
    {
        return $this->dropOff;
    }

    /**
     * Set odtArea
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\OdtArea $odtArea
     * @return OdtStop
     */
    public function setOdtArea(\Tisseo\DatawarehouseBundle\Entity\OdtArea $odtArea = null)
    {
        $this->odtArea = $odtArea;

        return $this;
    }

    /**
     * Get odtArea
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\OdtArea 
     */
    public function getOdtArea()
    {
        return $this->odtArea;
    }

    /**
     * Set stop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Stop $stop
     * @return OdtStop
     */
    public function setStop(\Tisseo\DatawarehouseBundle\Entity\Stop $stop = null)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Stop 
     */
    public function getStop()
    {
        return $this->stop;
    }
}
