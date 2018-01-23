<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\GridCalendarType;
use Tisseo\CoreBundle\Controller\CoreController;
use Tisseo\EndivBundle\Entity\GridCalendar;
use Tisseo\EndivBundle\Entity\LineVersion;

class CalendarController extends CoreController
{
    /*
     * Render Form
     *
     * This method is called through ajax request in order to display a new
     * fresh GridCalendarType form when a previous one has just been
     * submitted and validated.
     */
    public function renderFormAction()
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_GRID_CALENDAR');

        $gridCalendar = new GridCalendar();

        $form = $this->createForm(
            new GridCalendarType(),
            $gridCalendar
        );

        return $this->render(
            'TisseoPaonBundle:GridCalendar:form.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /*
     * Create
     *
     * This function is called though ajax request and will launch GridCalendarType
     * form validation process.
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_GRID_CALENDAR');

        $this->isAjax($request, Request::METHOD_POST);

        $gridCalendar = new GridCalendar();

        $form = $this->createForm(
            new GridCalendarType(),
            $gridCalendar
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            $gridCalendar = $form->getData();

            return $this->render(
                'TisseoPaonBundle:GridCalendar:new.html.twig',
                array(
                    'gridCalendar' => $gridCalendar
                )
            );
        }

        return $this->render(
            'TisseoPaonBundle:GridCalendar:form.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Edit
     *
     * @param int $lineVersionId
     *
     * If request's method is GET, display a pseudo-form (ajax/json) which
     * purpose is to create/delete GridCalendar and link GridMaskType to them.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_GRID_CALENDAR');

        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

        if ($request->isXmlHttpRequest() && $request->getMethod() === 'POST') {
            try {
                $data = json_decode($request->getContent(), true);
                $freshData = $lineVersionManager->updateGridCalendars($data, $lineVersionId);
                $gridCalendarManager->attachGridCalendars($freshData);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            } catch (\Exception $e) {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        $lineVersion = $lineVersionManager->findWithPreviousCalendars($lineVersionId);

        return $this->render(
            'TisseoPaonBundle:Calendar:edit.html.twig',
            array(
                'title' => 'tisseo.paon.calendar.title.edit',
                'lineVersion' => $lineVersion,
                'gridCalendars' => $gridCalendarManager->findRelatedGridMaskTypes($lineVersion->getGridCalendars(), $lineVersion->getId()),
                'gridMaskTypes' => $lineVersionManager->findUnlinkedGridMaskTypes($lineVersion),
            )
        );
    }
}
