<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stop
 */
class Stop
{
    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Waypoint
     */
    private $id;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Stop
     */
    private $masterStop;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\StopArea
     */
    private $stopArea;


    /**
     * Set id
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Waypoint $id
     * @return Stop
     */
    public function setId(\Tisseo\DatawarehouseBundle\Entity\Waypoint $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Waypoint 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set masterStop
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Stop $masterStop
     * @return Stop
     */
    public function setMasterStop(\Tisseo\DatawarehouseBundle\Entity\Stop $masterStop = null)
    {
        $this->masterStop = $masterStop;

        return $this;
    }

    /**
     * Get masterStop
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Stop 
     */
    public function getMasterStop()
    {
        return $this->masterStop;
    }

    /**
     * Set stopArea
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\StopArea $stopArea
     * @return Stop
     */
    public function setStopArea(\Tisseo\DatawarehouseBundle\Entity\StopArea $stopArea = null)
    {
        $this->stopArea = $stopArea;

        return $this;
    }

    /**
     * Get stopArea
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\StopArea 
     */
    public function getStopArea()
    {
        return $this->stopArea;
    }
    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Waypoint
     */
    private $waypoint;


    /**
     * Set waypoint
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Waypoint $waypoint
     * @return Stop
     */
    public function setWaypoint(\Tisseo\DatawarehouseBundle\Entity\Waypoint $waypoint = null)
    {
        $this->waypoint = $waypoint;

        return $this;
    }

    /**
     * Get waypoint
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Waypoint 
     */
    public function getWaypoint()
    {
        return $this->waypoint;
    }
}
