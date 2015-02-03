<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class CalendarController extends AbstractController
{
    private function buildForm($calendarId, $calendarManager)
    {
        $calendar = $calendarManager->find($calendarId);
        $form = $this->createForm(
            new LineVersionType(),
            $calendar,
            array(
                'action' => $this->generateUrl(
                    'tisseo_datawarehouse_calendar_edit',
                    array(
                        'calendarId' => $calendarId
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $calendarManager = $this->get('tisseo_datawarehouse.calendar_manager');
        if ($form->isValid()) {
            $calendarManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'calendar.created',
                    array(),
                    'default'
                )
            );
            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_calendar_list')
            );
        }
        return (null);
    }

    public function editAction(Request $request, $calendarId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');
        $calendarManager = $this->get('tisseo_datawarehouse.calendar_manager');
        $form = $this->buildForm($calendarId, $calendarManager);
        $render = $this->processForm($request, $form);
        if (!$render) {
            return $this->render(
                'TisseoDatawarehouseBundle:Calendar:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'title' => ($calendarId ? 'calendar.edit' : 'calendar.create')
                )
            );
        }
        return ($render);
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_CALENDAR');
        $calendarManager = $this->get('tisseo_datawarehouse.calendar_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:Calendar:list.html.twig',
            array(
                'pageTitle' => 'menu.calendar_manage',
                'calendars' => $calendarManager->findAll()
            )
        );
    }
}
