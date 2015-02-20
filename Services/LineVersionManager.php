<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;
use Tisseo\DatawarehouseBundle\Services\TripManager;
use \Doctrine\Common\Collections\ArrayCollection;

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

    public function findLineVersionsWithoutCalendars(\Datetime $now)
    {
        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.endDate is null')
            ->orWhere('lv.endDate > :now')
            ->setParameter('now', $now)
            ->getQuery();

        $lineVersions = new ArrayCollection($query->getResult());
        /*foreach($lineVersions as $lineVersion)
        {
            if (!$lineVersion->isNew())
                $lineVersions->removeElement($lineVersion);
        }*/

        return $this->sortLineVersionsByNumber($lineVersions->toArray());
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

    public function findPreviousLineVersion($lineVersion)
    {
        $query = $this->om->createQuery("
            SELECT lv FROM Tisseo\DatawarehouseBundle\Entity\LineVersion lv
            JOIN lv.line l
            WHERE l.number = ?1
            AND lv.id != ?2
        ");
        $query->setParameter(1, $lineVersion->getLine()->getNumber());
        $query->setParameter(2, $lineVersion->getId());

        $lineVersions = $query->getResult();

        $result = null;
        foreach($lineVersions as $lv)
        {
            if ($lv->getEndDate() === null)
                continue;
            if (($lineVersion->getEndDate() !== null && $lv->getEndDate > $lineVersion->getEndDate()) || (!empty($result) && $result->getEndDate() > $lv->getEndDate()))
                continue;
            $result = $lv;
        }
        
        return $result;
    }

    public function findCalendars($lineVersionId)
    {
        $lineVersion = $this->find($lineVersionId);

        if ($lineVersion->isNew())
        {
            $previousLineVersion = $this->findPreviousLineVersion($lineVersion);
            if (!empty($previousLineVersion))
            {
                $lineVersion->mergeGridCalendars($previousLineVersion);
            }
        }

        return $lineVersion;
    }

    public function findGridMaskTypes($lineVersion)
    {
        if ($lineVersion->getGridCalendars()->isEmpty())
        {
            $query = $this->om->createQuery("
                SELECT gmt FROM Tisseo\DatawarehouseBundle\Entity\GridMaskType gmt
                JOIN gmt.tripCalendars tc
                JOIN tc.trips t
                JOIN t.route r
                JOIN r.lineVersion lv
                WHERE lv.id = ?1
                GROUP BY gmt.id
            ");
        }
        else
        {
            /* 
             * SELECT GMT.* from grid_mask_type GMT 
             * JOIN trip_calendar TC ON TC.grid_mask_type_id = GMT.id 
             * JOIN trip T ON T.trip_calendar_id = TC.id 
             * JOIN route R ON R.id = T.route_id
             * JOIN line_version LV ON LV.id = R.line_version_id
             * JOIN grid_calendar GC ON GC.line_version_id = LV.id 
             * WHERE LV.id = 135
             * AND GMT.id NOT IN(
             *      SELECT GLCMT.grid_mask_type_id FROM grid_link_calendar_mask_type GLCMT
             *      JOIN grid_calendar GC ON GC.id = GLCMT.grid_calendar_id 
             *      WHERE GC.id IN(
             *          SELECT GC.id FROM grid_calendar GC WHERE GC.line_version_id = 135
             *      )
             * )
             * GROUP BY GMT.id
             */
            $query = $this->om->createQuery("
                SELECT gmt FROM Tisseo\DatawarehouseBundle\Entity\GridMaskType gmt
                JOIN gmt.tripCalendars tc
                JOIN tc.trips t
                JOIN t.route r
                JOIN r.lineVersion lv
                JOIN lv.gridCalendars gc
                WHERE lv.id = ?1
                AND gmt.id NOT IN(
                    SELECT IDENTITY(glcmt.gridMaskType) FROM Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType glcmt 
                    JOIN glcmt.gridCalendar sub_gc
                    WHERE sub_gc.id IN(
                        SELECT sub_sub_gc.id FROM Tisseo\DatawarehouseBundle\Entity\GridCalendar sub_sub_gc
                        JOIN sub_sub_gc.lineVersion sub_lv
                        WHERE sub_lv.id = ?1
                    )
                )
                GROUP BY gmt.id
            ");
        }
        $query->setParameter(1, $lineVersion->getId());

        return $query->getResult();
    }

    public function save(LineVersion $lineVersion)
    {
        $oldLineVersion = $this->findLineVersionByLine($lineVersion->getLine()->getId());
        if ($oldLineVersion->getEndDate === null)
            $oldLineVersion->closeDate($lineVersion->getStartDate());
        else if ($oldLineVersion->getEndDate() > $lineVersion->getStartDate())
            return array(false,'line_version.closure_error');

        $this->om->persist($oldLineVersion);
        $this->om->persist($lineVersion);
        $this->om->flush();

        $this->tripManager->deleteTrips($oldLineVersion);

        return array(true,'line_version.persisted');
    }

    public function close(LineVersion $lineVersion)
    {
        $this->tripManager->deleteTrips($lineVersion);
        $this->om->persist($lineVersion);
        $this->om->flush();

        return array(true,'line_version.closed');
    }

    public function persist(LineVersion $lineVersion)
    {
        $this->om->persist($lineVersion);
        $this->om->flush();

        return array(true,'line_version.persisted');
    }
}
