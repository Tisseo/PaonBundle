<?php

namespace Tisseo\DatawarehouseBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use CanalTP\SamCoreBundle\DataFixtures\ORM\UserTrait;

class FixturesUser extends AbstractFixture implements OrderedFixtureInterface
{
    use UserTrait;

    private $users = array(
        array(
            'id'        => null,
            'username'  => 'utilisateur TID',
            'firstname' => 'utilisateur',
            'lastname'  => 'TID',
            'email'     => 'user-tid@tisseo.fr',
            'password'  => 'tid',
            'roles'     => array('role-user-datawarehouse'),
            'customer'  => 'customer-tisseo'
        ),
        array(
            'id'        => null,
            'username'  => 'admin TID',
            'firstname' => 'admin',
            'lastname'  => 'TID',
            'email'     => 'admin-tid@tisseo.fr',
            'password'  => 'admin',
            'roles'     => array('role-admin-datawarehouse'),
            'customer'  => 'customer-tisseo'
        )
    );

    public function load(ObjectManager $om)
    {
        foreach ($this->users as $userData) {
            $userEntity = $this->createUser($om, $userData);
        }
        $om->flush();
    }

    /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 5;
    }
}
