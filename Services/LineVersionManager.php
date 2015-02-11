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
            ->where('lv.endDate is null')
            ->orWhere('lv.endDate < :now')
            ->setParameter('now', $now)
            ->getQuery();

        $results = $query->getResult();
        usort($results, function($val1, $val2) {
            $line1 = $val1->getLine();
            $line2 = $val2->getLine();
            if ($line1->getPriority() == $line2->getPriority())
                return strnatcmp($line1->getNumber(), $line2->getNumber());
            if ($line1->getPriority() > $line2->getPriority())
                return 1;
            if ($line1->getPriority() < $line2->getPriority())
                return -1;
        });
        return $results;
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
