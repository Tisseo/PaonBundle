<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\Calendar;

class CalendarManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {   
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:Calendar');
    }   

    public function findAll()
    {   
        return ($this->repository->findAll());
    }   

    public function find($calendarId)
    {   
        return empty($calendarId) ? null : $this->repository->find($calendarId);
    }

    public function save(Calendar $calendar)
    {
        $this->om->persist($calendar);
        $this->om->flush();
    }
}
