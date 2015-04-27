<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\Entity\LineGroupGis;


class LineGroupGisController extends AbstractController
{
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_GROUP_GIS');

        /** @var \Tisseo\EndivBundle\Services\LineGroupGisManager $lineGroupGisManager */
        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');

        return $this->render(
            'TisseoTidBundle:LineGroupGis:listLineGroupGis.html.twig',
            array(
                'pageTitle' => 'menu.schema_gis_group',
                'groups' => $lineGroupGisManager->findAll()
            )
        );
    }
}