<?php

namespace Tisseo\TidBundle\DataFixtures\ORM;

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
            'reference'     => 'user-tid',
            'application'   => 'app-tid',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_LIST_LINE_VERSION'
            )
        ),
        array(
            'name'          => 'Admin TID',
            'reference'     => 'admin-tid',
            'application'   => 'app-tid',
            'isEditable'    => true,
            'permissions'  => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_MANAGE_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_MANAGE_LINE_VERSION',
                'BUSINESS_MANAGE_GRID_CALENDAR',
                'BUSINESS_MANAGE_EXCEPTION',
                'BUSINESS_MANAGE_DATA_EXCHANGE'
            )
        ),
        array(
            'name'          => 'User TID IV',
            'reference'     => 'user-tid-iv',
            'application'   => 'app-tid',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_MANAGE_ASK_SCHEMA'
            )
        ),
        array(
            'name'          => 'User TID SIG',
            'reference'     => 'user-tid-sig',
            'application'   => 'app-tid',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_MANAGE_NEW_SCHEMA'
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
