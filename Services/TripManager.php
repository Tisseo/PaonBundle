<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\Trip;

class TripManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:Trip');
    }

    public function findAll()
    {
        return ($this->repository->findAll());
    }

    public function find($tripId)
    {
        return empty($tripId) ? null : $this->repository->find($tripId);
    }

    public function findOne($tripId)
    {
        return empty($tripId) ? null : $this->repository->findOne($tripId);
    }

    public function deleteTrips(LineVersion $lineVersion)
    {
         /**
        SELECT T.id, min(CE.start_date), max(CE.end_date) FROM calendar_element CE JOIN calendar_link CL ON CL.period_calendar_id = CE.calendar_id JOIN TRIP T ON T.id = CL.trip_id JOIN route R ON R.id = T.route_id JOIN line_version LV ON LV.id = R.line_version_id WHERE LV.id = 9 AND CE.start_date <= '2015-02-10' GROUP BY T.id;
         */
        $query = $this->repository->createQuery("
            SELECT t FROM Tisseo\DatawarehouseBundle\Entity\Trip t
            JOIN t.route r
            JOIN r.lineVersion lv
            JOIN t.calendarLinks cl
            JOIN cl.periodCalendar pc
            JOIN pc.calendarElements ce
            WHERE lv.id = ?1
            AND ce.start_date > ?2
            ORDER BY t.id
        ");
        $query->setParameter(1, $lineVersion->getId());
        $query->setParameter(2, $lineVersion->getEndDate());

        $trips = $query->getResult();

        $file = fopen('/tmp/test_tid.log','a+');
        foreach($trips as $trip)
        {
            //$this->om->persist($lineVersion);
            fwrite($file, "\n".$trip->getId());
        }
        fclose($file);
        //$this->om->flush();
    }

    public function save(Trip $trip)
    {
        $this->om->persist($trip);
        $this->om->flush();
    }
}
