<?php

/**
 * UserManager service for user related needs
 *
 * @author vdegroote
 */
namespace Tisseo\DatawarehouseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Translation\LoggingTranslator;

class UserManager
{
    private $om = null;
    private $translator = null;
    private $container = null;

    public function __construct(
        ObjectManager $om,
        LoggingTranslator $translator,
        $container
    )
    {
        $this->om = $om;
        $this->translator = $translator;
        $this->container = $container;
    }

    /**
     * Retrieve current user networks
     */
    public function getNetworks($user = null)
    {
        if ($user == null) {
            $user = $this->container->get('security.context')->getToken()->getUser();
        }
        if ($user === 'anon.') {
            return (array());
        }
        $perimeters = $user->getCustomer()->getPerimeters();

        if (count($perimeters) == 0) {
            throw new \Exception(
                $this->translator->trans(
                    'controller.default.navigation.no_networks',
                    array(),
                    'exceptions'
                )
            );
        }

        return $perimeters;
    }
}
