<?php

namespace Tisseo\PaonBundle\Controller;

use Tisseo\CoreBundle\Controller\CoreController;

class DefaultController extends CoreController
{
    public function indexAction()
    {
        return $this->render(
            'TisseoCoreBundle::container.html.twig',
            array(
                'pageTitle' => 'tisseo.paon.welcome',
                'bundle' => 'TisseoPaonBundle'
            )
        );
    }
}
