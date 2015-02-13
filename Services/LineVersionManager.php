<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class LineVersionManager extends SortManager
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
            ->where('lv.endDate is null')
            ->orWhere('lv.endDate > :now')
            ->setParameter('now', $now)
            ->getQuery();

        return $this->sortLineVersionsByNumber($query->getResult());
    }

    public function findLineVersionByLine($lineId) {
        if ($lineId == null)
            return null;

        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.line = :line')
            ->setParameter('line', $lineId)
            ->getQuery();

        try
        {
            $results = $query->getResult();
        }
        catch(\Exception $e)
        {
            return null;
        }

        $finalResult = null;
        foreach($results as $result)
        {
            if ($result->getEndDate() === null)
                return $result;
            else if ($finalResult !== null && ($finalResult->getEndDate() < $result->getEndDate()))
                continue;
            $finalResult = $result;
        }

        return $finalResult;
    }

    public function save(LineVersion $lineVersion)
    {
        $this->om->persist($lineVersion);
        $this->om->flush();
    }
}
