<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\Line;

class LineManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {   
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:Line');
    }   

    public function findAll()
    {   
        return ($this->repository->findAll());
    }   

    public function find($lineId)
    {   
        return empty($lineId) ? null : $this->repository->find($lineId);
    }

    public function findAllLinesByMode()
    {
        $query = $this->repository->createQueryBuilder('l')
            ->orderBy('l.number', 'ASC')
            ->orderBy('l.physicalMode', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    public function save(Line $line)
    {
        $this->om->persist($line);
        $this->om->flush();
    }
}
