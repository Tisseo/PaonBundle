<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Tisseo\TidBundle\Form\Type\GridCalendarType;
use Tisseo\EndivBundle\Entity\GridCalendar;
use Tisseo\EndivBundle\Entity\LineVersion;

class CalendarController extends AbstractController
{
    /*
     * buildForm
     * @param LineVersion $lineVersion
     * @return Form $form
     *
     * Build a new GridCalendarType form.
     */
    private function buildForm(LineVersion $lineVersion)
    {
        $gridCalendar = new GridCalendar($lineVersion);
        $form = $this->createForm(
            new GridCalendarType(),
            $gridCalendar,
            array(
                'action' => $this->generateUrl(
                    'tisseo_tid_calendar_edit',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                )
            )
        );
        return ($form);
    }

    /*
     * processForm
     * @param Request $request
     * @param Form $form
     * @param integer $lineVersionId
     *
     * If form is valid, return a new gridCalendar rendered in a specific view.
     * Else, return the actual form view with errors.
     */
    private function processForm(Request $request, $form, $lineVersionId)
    {
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
            $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

            $lineVersion = $lineVersionManager->find($lineVersionId);
            $gridCalendar = $form->getData();
            $gridCalendar->setLineVersion($lineVersion);
            $gridCalendarManager->save($gridCalendar);

            return $this->render(
                'TisseoTidBundle:GridCalendar:calendar.html.twig',
                array(
                    'gridCalendar' => $gridCalendar
                )
            );
        }

        return $this->render(
            'TisseoTidBundle:GridCalendar:form.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /*
     * renderFormAction
     * @param integer $lineVersionId
     *
     * This method is called through ajax request in order to display a new
     * fresh form when a previous one had just been submitted and validated.
     */
    public function renderFormAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        $form = $this->buildForm($lineVersion);
        return $this->render(
            'TisseoTidBundle:GridCalendar:form.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * editAction
     * @param Request $request
     * @param integer $lineVersionId
     *
     * If request's method is GET, display a pseudo-form (ajax/json) which
     * purpose is to create/delete GridCalendar and link GridMaskType to them.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');

        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
            $jsonDecode = new JsonDecode();
            $data = $jsonDecode->decode($request->getContent(), JsonEncoder::FORMAT);
            $lineVersionManager->updateGridCalendars(array_keys(get_object_vars($data)), $lineVersionId);
            $gridCalendarManager->attachGridCalendars($data);

            return $this->redirect(
                $this->generateUrl('tisseo_tid_calendar_list')
            );
        }

        $lineVersion = $lineVersionManager->findWithPreviousCalendars($lineVersionId);
        $gridCalendars = $gridCalendarManager->findRelatedGridMaskTypes($lineVersion->getGridCalendars(), $lineVersion->getId());
        $gridMaskTypes = $lineVersionManager->findUnlinkedGridMaskTypes($lineVersion);

        return $this->render(
            'TisseoTidBundle:LineVersion:calendars.html.twig',
            array(
                'title' => 'menu.grid_calendar_manage',
                'lineVersionId' => $lineVersion->getId(),
                'gridCalendars' => $gridCalendars,
                'gridMaskTypes' => $gridMaskTypes,
            )
        );
    }

    /*
     * createAction
     * @param Request $request
     * @param ineteger $lineVersionId
     *
     * This function is called though ajax request and will launch GridCalendarType
     * form validation process.
     */
    public function createAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_CALENDAR');

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
            $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
            $lineVersion = $lineVersionManager->find($lineVersionId);

            return $this->processForm($request, $this->buildForm($lineVersion), $lineVersion->getId());
        }
        return (null);
    }

    /*
     * listAction
     * @param Request $request
     *
     * This function render the list of LineVersions which GridCalendars can be
     * edited.
     */
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_CALENDAR');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        return $this->render(
            'TisseoTidBundle:Calendar:list.html.twig',
            array(
                'pageTitle' => 'menu.calendar_manage',
                'lineVersions' => $lineVersionManager->findActiveLineVersions(new \Datetime(), false, true)
            )
        );
    }
}
