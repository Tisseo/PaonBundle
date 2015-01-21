<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OdtArea
 */
class OdtArea
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Waypoint
     */
    private $id;


    /**
     * Set name
     *
     * @param string $name
     * @return OdtArea
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
     * @param string $comment
     * @return OdtArea
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
     * Set id
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Waypoint $id
     * @return OdtArea
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
}
