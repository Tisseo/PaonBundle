<?php

namespace Tisseo\PaonBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use CanalTP\SamCoreBundle\DataFixtures\ORM\CustomerTrait;

class FixturesCustomer extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use CustomerTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $om)
    {
        $navitiaToken = $this->container->getParameter('nmm.navitia.token');
        $samFixturePerimeters = $this->container->getParameter('sam_fixture_perimeters');

        $this->addCustomerToApplication($om, 'app-paon', 'customer-tisseo', $navitiaToken);

        foreach ($samFixturePerimeters as $key => $value) {
            $this->addPerimeterToCustomer($om, $value['coverage'], $value['network'], 'customer-tisseo');
        }
        $om->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
