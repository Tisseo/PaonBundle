<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends AbstractController
{
    public function indexAction($externalNetworkId = null)
    {
        return $this->render('TisseoTidBundle:Default:index.html.twig');
    }
}
