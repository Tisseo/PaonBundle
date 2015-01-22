<?php

namespace Tisseo\DatawarehouseBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use CanalTP\SamEcoreUserManagerBundle\Entity\User;
use CanalTP\SamCoreBundle\DataFixtures\ORM\RoleTrait;

class FixturesRole extends AbstractFixture implements OrderedFixtureInterface
{
    use RoleTrait;

    private $roles = array(
        array(
            'name'          => 'User TID',
            'reference'     => 'user-datawarehouse',
            'application'   => 'app-datawarehouse',
            'isEditable'    => true,
            'permissions'   => array()
        ),
        array(
            'name'          => 'Admin TID',
            'reference'     => 'admin-datawarehouse',
            'application'   => 'app-datawarehouse',
            'isEditable'    => true,
            'permissions'  => array(
                'BUSINESS_MANAGE_CUSTOMER'
            )
        )
    );

    public function load(ObjectManager $om)
    {
         foreach ($this->roles as $role) {
            $this->createApplicationRole($om,  $role);
        }
        $om->flush();
    }

    /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 2;
    }
}
