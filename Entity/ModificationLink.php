<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModificationLink
 */
class ModificationLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var date
     */
    private $date;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\Modification
     */
    private $modification;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $lineVersion;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $resolvedIn;

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
     * Set date
     *
     * @param \DateTime $date
     * @return ModificationLink
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set modification
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\Modification $modification
     * @return ModificationLink
     */
    public function setModification(\Tisseo\DatawarehouseBundle\Entity\Modification $modification = null)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\Modification 
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return ModificationLink
     */
    public function setLineVersion(LineVersion $lineVersion = null)
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

    /**
     * Set resolvedIn
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $resolvedIn
     * @return ModificationLink
     */
    public function setResolvedIn(LineVersion $resolvedIn = null)
    {
        $this->resolvedIn = $resolvedIn;

        return $this;
    }

    /**
     * Get resolvedIn
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\LineVersion 
     */
    public function getResolvedIn()
    {
        return $this->resolvedIn;
    }

}
