<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;
use Tisseo\DatawarehouseBundle\Services\TripManager;

class LineVersionManager extends SortManager
{
    private $om = null;
    private $repository = null;
    private $tripManager = null;

    public function __construct(ObjectManager $om, TripManager $tripManager)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:LineVersion');
        $this->tripManager = $tripManager;
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
            else if ($finalResult !== null && ($finalResult->getEndDate() > $result->getEndDate()))
                continue;
            $finalResult = $result;
        }

        return $finalResult;
    }

    public function save(LineVersion $lineVersion)
    {
        $oldLineVersion = $this->findLineVersionByLine($lineVersion->getLine()->getId());
        if ($oldLineVersion->getEndDate === null)
        {
            $oldLineVersion->closeDate($lineVersion->getStartDate());
            $this->persist($oldLineVersion);
        }

        $this->tripManager->deleteTrips($oldLineVersion);

        $this->om->persist($lineVersion);
        $this->om->flush();
    }

    public function close(LineVersion $lineVersion)
    {
        $this->tripManager->deleteTrips($lineVersion);
        $this->om->persist($lineVersion);
        $this->om->flush();
    }

    public function persist(LineVersion $lineVersion)
    {
        $this->om->persist($lineVersion);
        $this->om->flush();
    }
}
