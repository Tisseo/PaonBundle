<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alias
 */
class Alias
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
     * @var \Tisseo\DatawarehouseBundle\Entity\StopArea
     */
    private $stopArea;


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
     * @return Alias
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
     * Set stopArea
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\StopArea $stopArea
     * @return Alias
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
}
