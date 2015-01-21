<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TisseoDatawarehouseBundle:Default:index.html.twig', array('name' => $name));
    }
}
