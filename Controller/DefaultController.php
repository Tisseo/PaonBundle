<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends AbstractController
{
    public function indexAction($externalNetworkId = null)
    {
        return $this->render('TisseoPaonBundle:Default:index.html.twig');
    }
}
