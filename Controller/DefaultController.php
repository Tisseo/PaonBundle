<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends AbstractController
{
    public function indexAction($externalNetworkId = null)
    {
        return $this->render('TisseoDatawarehouseBundle:Default:index.html.twig');
    }
}
