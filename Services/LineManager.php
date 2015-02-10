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

    public function findAllLinesByMode()
    {
        $query = $this->repository->createQueryBuilder('l')
            ->orderBy('l.number', 'ASC')
            ->orderBy('l.physicalMode', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    public function save(Line $line)
    {
        $this->om->persist($line);
        $this->om->flush();
    }
}
