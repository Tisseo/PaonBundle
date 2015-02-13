<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\Printing;

class PrintingManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:Printing');
    }

    public function findAll()
    {
        return ($this->repository->findAll());
    }

    public function find($printingId)
    {
        return empty($printingId) ? null : $this->repository->find($printingId);
    }

    public function save(Printing $printing)
    {
        $this->om->persist($printing);
        $this->om->flush();
    }
}
