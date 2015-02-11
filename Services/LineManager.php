<?php

namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\DatawarehouseBundle\Entity\Line;

class LineManager
{
    private $om = null;
    private $repository = null;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('TisseoDatawarehouseBundle:Line');
    }

    public function findAll()
    {
        return ($this->repository->findAll());
    }

    public function find($lineId)
    {
        return empty($lineId) ? null : $this->repository->find($lineId);
    }

    public function alreadyExists($number, $id = null) {
        if ($id !== null)
        {
            $line = $this->findExistingNumber($number, $id);
        }
        else
        {
            $line = $this->repository->findBy(
                array(
                    'number' => $number
                )
            );
        }
        return empty($line) ? false : true;
    }

    public function findExistingNumber($number, $id)
    {
        $query = $this->repository->createQueryBuilder('l')
            ->where('l.number = :number AND l.id != :id')
            ->setParameter('number', $number)
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    public function findAllLinesByPriority()
    {
        $query = $this->repository->createQueryBuilder('l')
            ->addOrderBy('l.priority', 'ASC')
            ->getQuery();

        $results = $query->getResult();
        usort($results, function($val1, $val2) {
            if ($val1->getPriority() == $val2->getPriority())
                return strnatcmp($val1->getNumber(), $val2->getNumber());
            if ($val1->getPriority() > $val2->getPriority())
                return 1;
            if ($val1->getPriority() < $val2->getPriority())
                return -1;
        });
        
        return $results;
    }

    public function save(Line $line)
    {
        $this->om->persist($line);
        $this->om->flush();
    }
}
