<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class OfferManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {   
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:LineVersion');
    }   

    public function findAll()
    {   
        return ($this->repository->findAll());
    }   

    public function find($offerId)
    {   
        return empty($offerId) ? null : $this->repository->find($offerId);
    }

    public function findActiveOffers()
    {
        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.endDate IS NULL')
            ->orderBy('lv.name', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function save(LineVersion $offer)
    {
        $this->om->persist($offer);
        $this->om->flush();
    }
}
