<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ExportController extends AbstractController
{
    public function timetableAction()
    {
        $this->isGranted('BUSINESS_MANAGE_EXPORT_TIMETABLE');
        $exportManager = $this->get('tisseo_tid.export_manager');
        return $this->render(
            'TisseoTidBundle:Export:timetable.html.twig',
            array(
                'pageTitle' => 'menu.export_manage',
                'export' => $exportManager->find("timetable")
            )
        );
    }
}
