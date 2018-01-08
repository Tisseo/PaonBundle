<?php

namespace Tisseo\PaonBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use CanalTP\SamCoreBundle\DataFixtures\ORM\UserTrait;

class FixturesUser extends AbstractFixture implements OrderedFixtureInterface
{
    use UserTrait;

    private $users = array(
        array(
            'id' => null,
            'username' => 'consult PAON',
            'firstname' => 'utilisateur',
            'lastname' => 'PAON',
            'email' => 'consult-paon@tisseo.fr',
            'password' => 'paon',
            'roles' => array('role-consult-paon'),
            'customer' => 'customer-tisseo'
        ),
        array(
            'id' => null,
            'username' => 'admin PAON',
            'firstname' => 'admin',
            'lastname' => 'PAON',
            'email' => 'admin-paon@tisseo.fr',
            'password' => 'admin',
            'roles' => array('role-admin-paon'),
            'customer' => 'customer-tisseo'
        ),
        array(
            'id' => null,
            'username' => 'user_paon_iv',
            'firstname' => 'user paon iv',
            'lastname' => 'PAON',
            'email' => 'user_paon_iv@tisseo.fr',
            'password' => 'admin',
            'roles' => array('role-user-paon-iv'),
            'customer' => 'customer-tisseo'
        ),
        array(
            'id' => null,
            'username' => 'user_paon_sig',
            'firstname' => 'user paon sig',
            'lastname' => 'PAON',
            'email' => 'user_paon_sig@tisseo.fr',
            'password' => 'admin',
            'roles' => array('role-user-paon-sig'),
            'customer' => 'customer-tisseo'
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
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
