<?php

namespace Tisseo\PaonBundle\DataFixtures\ORM;

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
            'name'          => 'Consult PAON',
            'reference'     => 'consult-paon',
            'application'   => 'app-paon',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_LIST_GROUP_GIS'
            )
        ),
        array(
            'name'          => 'Admin PAON',
            'reference'     => 'admin-paon',
            'application'   => 'app-paon',
            'isEditable'    => true,
            'permissions'  => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_MANAGE_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_MANAGE_LINE_VERSION',
                'BUSINESS_MANAGE_GRID_CALENDAR',
                'BUSINESS_MANAGE_EXCEPTION',
                'BUSINESS_MANAGE_DATA_EXCHANGE',
                'BUSINESS_MANAGE_DATA_EXCHANGE_ROOT',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_MANAGE_NEW_SCHEMA',
                'BUSINESS_MANAGE_ASK_SCHEMA',
                'BUSINESS_LIST_GROUP_GIS',
                'BUSINESS_MANAGE_GROUP_GIS'

            )
        ),
        array(
            'name'          => 'User PAON IV',
            'reference'     => 'user-paon-iv',
            'application'   => 'app-paon',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_MANAGE_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_MANAGE_LINE_VERSION',
                'BUSINESS_MANAGE_GRID_CALENDAR',
                'BUSINESS_MANAGE_EXCEPTION',
                'BUSINESS_MANAGE_DATA_EXCHANGE',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_MANAGE_ASK_SCHEMA',
                'BUSINESS_LIST_GROUP_GIS',
                'BUSINESS_MANAGE_GROUP_GIS'
            )
        ),
        array(
            'name'          => 'User PAON SIG',
            'reference'     => 'user-paon-sig',
            'application'   => 'app-paon',
            'isEditable'    => true,
            'permissions'   => array(
                'BUSINESS_LIST_LINE',
                'BUSINESS_LIST_LINE_VERSION',
                'BUSINESS_LIST_SCHEMA',
                'BUSINESS_MANAGE_NEW_SCHEMA',
                'BUSINESS_LIST_GROUP_GIS',
                'BUSINESS_MANAGE_GROUP_GIS'
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
