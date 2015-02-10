<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class LineVersionManager
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

    public function find($lineVersionId)
    {
        return empty($lineVersionId) ? null : $this->repository->find($lineVersionId);
    }

    public function findOne($lineVersionId)
    {
        return empty($lineVersionId) ? null : $this->repository->findOne($lineVersionId);
    }

    public function findActiveLineVersions(\Datetime $now)
    {
        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.endDate IS NULL')
            ->orWhere('lv.endDate < :now')
            ->orderBy('lv.name', 'ASC')
            ->setParameter('now', $now)
            ->getQuery();

        return $query->getResult();
    }

    public function findLineVersionByLine($lineId) {
        if ($lineId == null)
            return null;

        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.line = :line')
            ->andWhere('lv.endDate IS NULL')
            ->setParameter('line', $lineId)
            ->getQuery();

        try
        {
            $result = $query->getSingleResult();
        }
        catch(\Exception $e)
        {
            $result = null;
        }
        return $result;
    }

    public function save(LineVersion $lineVersion)
    {
        $this->om->persist($lineVersion);
        $this->om->flush();
    }
}
