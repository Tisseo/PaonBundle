<?php

namespace Tisseo\DatawarehouseBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    private function addDivider($menu)
    {
        $menu->addChild(
            "",
            array(
                'attributes' => array('class' => 'divider')
            )
        );
    }

    public function mttMenu(FactoryInterface $factory, array $options)
    {
        $securityContext = $this->container->get('security.context');
        $translator = $this->container->get('translator');
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $user = $securityContext->getToken()->getUser();
        if ($user != 'anon.') {
            $userManager = $this->container->get('tisseo_datawarehouse.user');
        }
        
        if ($securityContext->isGranted(array('BUSINESS_LIST_LINE', 'BUSINESS_MANAGE_LINE'))) {
            $menu->addChild(
                "line_management",
                array(
                    'label' => $translator->trans('menu.line_manage'),
                    'route' => 'tisseo_datawarehouse_line_list'
                )
            );
        }
        return $menu;
    }
}
