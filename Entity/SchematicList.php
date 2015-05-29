<?php

namespace Tisseo\PaonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tisseo\EndivBundle\Entity\Schematic;

/**
 * SchematicList
 * This entity is not mapped with doctrine
 */
class SchematicList
{
    /** @var  \Doctrine\Common\Collections\ArrayCollection */
    public $schematics;

    public function __construct()
    {
        $this->schematics = new ArrayCollection();
    }

    public function setSchematics(Schematic $schematic)
    {
        $this->schematics[] = $schematic;
    }

    public function getSchematics()
    {
        return $this->schematics;
    }
}