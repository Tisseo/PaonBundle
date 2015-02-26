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

    /**
     * findLastLineVersionOfLine
     * @param integer $lineId
     * @return LineVersion or null
     *
     * Return the last version of LineVersion associated to the Line
     */
    public function findLastLineVersionOfLine($lineId) {
        $finalResult = null;

        if ($lineId == null)
            return $finalResult;

        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.line = :line')
            ->setParameter('line', $lineId)
            ->getQuery();

        try {
            $results = $query->getResult();
        } catch(\Exception $e) {
            return $finalResult;
        }

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

    /**
     * findPreviousLineVersion
     * @param LineVersion $lineVersion
     * @return LineVersion or null
     *
     * Find an hypothetical previous version of the LineVersion passed in 
     * parameter.
     */
    public function findPreviousLineVersion(LineVersion $lineVersion)
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

    /**
     * findWithPreviousCalendars 
     * @param integer $lineVersionId
     * @return LineVersion
     *
     * Find a LineVersion using lineVersionId.
     * If the returned LineVersion is new (i.e. no gridCalendars):
     *  - get its hypothetical previous LineVersion from database
     *  - if found, create new gridCalendars using previous LineVersion gridCalendars
     */
    public function findWithPreviousCalendars($lineVersionId)
    {
        $lineVersion = $this->find($lineVersionId);
        if ($lineVersion->isNew())
        {
            $previousLineVersion = $this->findPreviousLineVersion($lineVersion);
            if ($previousLineVersion !== null)
            {
                $lineVersion->mergeGridCalendars($previousLineVersion);
                $this->om->persist($lineVersion);
                $this->om->flush();
            }
        }

        return $lineVersion;
    }

    /*
     * findActiveLineVersions
     * @param Datetime $now
     * @return Collection $lineVersions
     *
     * Find LineVersion which are considered as active according to the current 
     * date passed as parameter.
     */
    public function findActiveLineVersions(\Datetime $now)
    {
        $query = $this->repository->createQueryBuilder('lv')
            ->where('lv.endDate is null')
            ->orWhere('lv.endDate > :now')
            ->setParameter('now', $now)
            ->getQuery();

        return $this->sortLineVersionsByNumber($query->getResult());
    }

    /*
     * findUnlinkedGridMaskTypes
     * @param LineVersion $lineVersion
     *
     * Find GridMaskTypes and their TripCalendars/Trips related to one 
     * LineVersion.
     */
    public function findUnlinkedGridMaskTypes(LineVersion $lineVersion)
    {
        /* if no gridCalendars linked to this lineVersion, search only for all related gridMaskTypes */
        $notLinked = true;
        foreach($lineVersion->getGridCalendars() as $gridCalendar)
        {
            if (!$gridCalendar->getGridLinkCalendarMaskTypes()->isEmpty())
            {
                $notLinked = false;
                break;
            }
        }
        if ($notLinked)
        {
            $query = $this->om->createQuery("
                SELECT gmt FROM Tisseo\DatawarehouseBundle\Entity\GridMaskType gmt
                JOIN gmt.tripCalendars tc
                JOIN tc.trips t
                JOIN t.route r
                JOIN r.lineVersion lv
                WHERE lv.id = ?1
                GROUP BY gmt.id
                ORDER BY gmt.id
            ");
        }
        /* else, search for all related gridMaskTypes which aren't already linked to a gridCalendar */
        else
        {
            $query = $this->om->createQuery("
                SELECT gmt FROM Tisseo\DatawarehouseBundle\Entity\GridMaskType gmt
                JOIN gmt.tripCalendars tc
                JOIN tc.trips t
                JOIN t.route r
                WHERE IDENTITY(r.lineVersion) = ?1
                AND gmt.id NOT IN(
                    SELECT IDENTITY(glcmt.gridMaskType) FROM Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType glcmt
                    JOIN glcmt.gridCalendar gc
                    WHERE IDENTITY(gc.lineVersion) = ?1
                )
                GROUP BY gmt.id
                ORDER BY gmt.id
            ");
        }
        $query->setParameter(1, $lineVersion->getId());

        $gmts = $query->getResult();
        $result = array();
        $cpt = 0;

        foreach($gmts as $gmt)
        {
            $result[$cpt] = array($gmt, array());
            
            $query = $this->om->createQuery("
                SELECT tc, count(t) FROM Tisseo\DatawarehouseBundle\Entity\TripCalendar tc
                JOIN tc.trips t
                JOIN t.route r
                JOIN r.lineVersion lv
                JOIN tc.gridMaskType gmt
                WHERE lv.id = ?1
                AND gmt.id = ?2
                GROUP BY tc.id
            ");
            
            $query->setParameter(1, $lineVersion->getId());
            $query->setParameter(2, $gmt->getId());

            $tripCalendars = $query->getResult();
            foreach($tripCalendars as $tripCalendar)
            {
                $result[$cpt][1][] = $tripCalendar;
            }
            $cpt++;
        }

        return $result;
    }

    /*
     * updateGridCalendars
     * @param array $gridCalendarIds
     * @param integer $lineVersionId
     *
     * Synchronize GridCalendars to a specific LineVersion according to values 
     * returned from calendars form view. (i.e. delete GridCalendars if their id 
     * is not present in $gridCalendarsIds)
     */
    public function updateGridCalendars($gridCalendarIds, $lineVersionId)
    {
        $lineVersion = $this->find($lineVersionId);
        $sync = false;
        foreach($lineVersion->getGridCalendars() as $gridCalendar)
        {
            if (!in_array($gridCalendar->getId(), $gridCalendarIds))
            {
                $sync = true;
                $lineVersion->removeGridCalendar($gridCalendar);
            }
        }

        if ($sync)
            $this->om->persist($lineVersion);
    }

    /*
     * create
     * @param LineVersion $lineVersion
     *
     * Save a LineVersion after :
     *  - closing previous LineVersion if it exists (using current LineVersion 
     *  startDate
     *  - deleting all trips which don't belong anymore to the previous 
     *  LineVersion
     */
    public function create(LineVersion $lineVersion)
    {
        $oldLineVersion = $this->findLastLineVersionOfLine($lineVersion->getLine()->getId());
        if ($oldLineVersion->getEndDate() === null)
            $oldLineVersion->closeDate($lineVersion->getStartDate());
        else if ($oldLineVersion->getEndDate() > $lineVersion->getStartDate())
            return array(false,'line_version.closure_error');

        $this->om->persist($oldLineVersion);
        $this->om->persist($lineVersion);
        $this->om->flush();

        $this->tripManager->deleteTrips($oldLineVersion);

        return array(true,'line_version.persisted');
    }

    /*
     * close
     * @param LineVersion $lineVersion
     * @return array(boolean, string)
     * Save a LineVersion into database after all trips which don't belong to it 
     * anymore have been deleted.
     */
    public function close(LineVersion $lineVersion)
    {
        $this->tripManager->deleteTrips($lineVersion);
        $this->om->persist($lineVersion);
        $this->om->flush();

        return array(true,'line_version.closed');
    }

    /*
     * save
     * @param LineVersion $lineVersion
     * @return array(boolean, string)
     *
     * Persist and save a LineVersion into database.
     */
    public function save(LineVersion $lineVersion)
    {
        $this->om->persist($lineVersion);
        $this->om->flush();

        return array(true,'line_version.persisted');
    }
}
