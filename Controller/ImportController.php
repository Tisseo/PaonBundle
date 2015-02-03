<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ImportController extends AbstractController
{
    public function fichorAction()
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORT_FICHOR');
        $importManager = $this->get('tisseo_datawarehouse.import_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:Import:fichor.html.twig',
            array(
                'pageTitle' => 'menu.import_manage',
                'import' => $importManager->find("fichor")
            )
        );
    }
}
