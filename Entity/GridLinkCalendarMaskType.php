<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GridLinkCalendarMaskType
 */
class GridLinkCalendarMaskType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\GridCalendar
     */
    private $gridCalendar;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\GridMaskType
     */
    private $gridMaskType;


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
     * Set active
     *
     * @param boolean $active
     * @return GridLinkCalendarMaskType
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set gridCalendar
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendar
     * @return GridLinkCalendarMaskType
     */
    public function setGridCalendar(\Tisseo\DatawarehouseBundle\Entity\GridCalendar $gridCalendar = null)
    {
        $this->gridCalendar = $gridCalendar;

        return $this;
    }

    /**
     * Get gridCalendar
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\GridCalendar 
     */
    public function getGridCalendar()
    {
        return $this->gridCalendar;
    }

    /**
     * Set gridMaskType
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridMaskType $gridMaskType
     * @return GridLinkCalendarMaskType
     */
    public function setGridMaskType(\Tisseo\DatawarehouseBundle\Entity\GridMaskType $gridMaskType = null)
    {
        $this->gridMaskType = $gridMaskType;

        return $this;
    }

    /**
     * Get gridMaskType
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\GridMaskType 
     */
    public function getGridMaskType()
    {
        return $this->gridMaskType;
    }
}
