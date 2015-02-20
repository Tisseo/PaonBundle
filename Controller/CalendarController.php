<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Tisseo\DatawarehouseBundle\Form\Type\GridCalendarType;
use Tisseo\DatawarehouseBundle\Entity\GridCalendar;

class CalendarController extends AbstractController
{
    private function buildForm($lineVersion)
    {
        $gridCalendar = new GridCalendar($lineVersion);
        $form = $this->createForm(
            new GridCalendarType(),
            $gridCalendar,
            array(
                'action' => $this->generateUrl(
                    'tisseo_datawarehouse_grid_calendar_create',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form, $lineVersionId)
    {
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $gridCalendarManager = $this->get('tisseo_datawarehouse.grid_calendar_manager');
            $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
            $lineVersion = $lineVersionManager->find($lineVersionId);
            $gridCalendar = $form->getData();
            $gridCalendar->setLineVersion($lineVersion);
            $gridCalendarManager->persist($gridCalendar);

            return $this->render(
                'TisseoDatawarehouseBundle:LineVersion:calendars.html.twig',
                array(
                    'title' => 'menu.grid_calendar_manage',
                    'lineVersion' => $lineVersion,
                    'gridCalendars' => $lineVersion->getGridCalendars(),
                    'gridMaskTypes' => $lineVersionManager->findGridMaskTypes($lineVersion)
                )
            );
        }
        return (null);
    }

    public function editAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
            $gridCalendarManager = $this->get('tisseo_datawarehouse.grid_calendar_manager');
            $jsonDecode = new JsonDecode();
            $data = $jsonDecode->decode($request->getContent(), JsonEncoder::FORMAT);
            $gridCalendarManager->attachGridCalendars($data);
            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_calendar_list')
            );
        }
        else
        {
            $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
            $lineVersion = $lineVersionManager->findCalendars($lineVersionId);
            return $this->render(
                'TisseoDatawarehouseBundle:LineVersion:calendars.html.twig',
                array(
                    'title' => 'menu.grid_calendar_manage',
                    'lineVersion' => $lineVersion,
                    'gridCalendars' => $lineVersion->getGridCalendars(),
                    'gridMaskTypes' => $lineVersionManager->findGridMaskTypes($lineVersion)
                )
            );
        }
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_CALENDAR');
        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:Calendar:list.html.twig',
            array(
                'pageTitle' => 'menu.calendar_manage',
                'lineVersions' => $lineVersionManager->findLineVersionsWithoutCalendars(new \Datetime())
            )
        );
    }

    public function createAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');
        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        $lineVersion = $lineVersionManager->findCalendars($lineVersionId);

        $form = $this->buildForm($lineVersion);
        $render = $this->processForm($request, $form, $lineVersion->getId());
        if (!$render)
        {
            return $this->render(
                'TisseoDatawarehouseBundle:GridCalendar:form.html.twig',
                array(
                    'form' => $form->createView()
                )
            );
        }
        else {
            return($render);
        }
    }
}
