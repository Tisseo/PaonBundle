<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Tisseo\DatawarehouseBundle\Entity\GridCalendar;
use Tisseo\DatawarehouseBundle\Entity\GridMaskType;
use Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType;

class GridCalendarManager extends SortManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:GridCalendar');
    }

    public function findAll()
    {
        return ($this->repository->findAll());
    }

    public function find($gridCalendarId)
    {
        return empty($gridCalendarId) ? null : $this->repository->find($gridCalendarId);
    }

    public function findOne($gridCalendarId)
    {
        return empty($gridCalendarId) ? null : $this->repository->findOne($gridCalendarId);
    }

    public function attachGridCalendars($data)
    {
        $gridMaskTypeRepository = $this->om->getRepository('TisseoDatawarehouseBundle:GridMaskType');
        foreach($data as $gridCalendarId => $gridMaskTypeIds)
        {
            if (!empty($gridMaskTypeIds))
            {
                $gridCalendar = $this->repository->find($gridCalendarId);
                foreach($gridMaskTypeIds as $gridMaskTypeId)
                {
                    $gridMaskType = $gridMaskTypeRepository->find($gridMaskTypeId);
                    $gridLinkCalendarMaskType = new GridLinkCalendarMaskType($gridCalendar, $gridMaskType, true);
                    $this->om->persist($gridLinkCalendarMaskType);
                } 
            }
        }
        $this->om->flush();
    }

    public function persist(GridCalendar $gridCalendar)
    {
        $this->om->persist($gridCalendar);
        $this->om->flush();

        return true;
    }
}
