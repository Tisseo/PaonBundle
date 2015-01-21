<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangeCauseLink
 */
class ChangeCauseLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\ChangeCause
     */
    private $changeCause;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $lineVersion;


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
     * Set changeCause
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\ChangeCause $changeCause
     * @return ChangeCauseLink
     */
    public function setChangeCause(\Tisseo\DatawarehouseBundle\Entity\ChangeCause $changeCause = null)
    {
        $this->changeCause = $changeCause;

        return $this;
    }

    /**
     * Get changeCause
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\ChangeCause 
     */
    public function getChangeCause()
    {
        return $this->changeCause;
    }

    /**
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return ChangeCauseLink
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
