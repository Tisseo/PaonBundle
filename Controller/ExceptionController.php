<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tisseo\TidBundle\Form\Type\CommentType;

class ExceptionController extends AbstractController
{
    private function buildForm()
    {
        $form = $this->createForm(
            new CommentType(),
            null,
            array()
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $response = new JsonResponse();
            $response->setData(
                array(
                    'label' => $form['label']->getData(),
                    'commentText' => $form['commentText']->getData()
                )
            );
            return $response;
        }
        return $this->render(
            'TisseoTidBundle:Comment:form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'comment.create'
            )
        );
    }

    /**
     * editAction
     * @param Request $request
     * @param integer $lineVersionId
     *
     * If request's method is GET, display a pseudo-form (ajax/json) which
     * purpose is to create/delete comments and link on trips.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');

        $tripManager = $this->get('tisseo_endiv.trip_manager');
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');

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

        $lineVersion = $lineVersionManager->find($lineVersionId);

        return $this->render(
            'TisseoTidBundle:Exception:edit.html.twig',
            array(
                'title' => 'menu.comment_manage',
                'lineVersion' => $lineVersion,
                'data' => $gridCalendarManager->findRelatedTrips($lineVersion->getGridCalendars())
            )
        );
    }

    /*
     * listAction
     * @param Request $request
     *
     * This function render the list of LineVersions with GridCalendars.
     */
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_EXCEPTION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        return $this->render(
            'TisseoTidBundle:Exception:list.html.twig',
            array(
                'pageTitle' => 'menu.exception_manage',
                'lineVersions' => $lineVersionManager->findActiveLineVersions(new \Datetime(), true)
            )
        );
    }

    /*
     * Comment action
     * @param Request $request
     *
     * Render a new comment form
     */
    public function commentAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');
        return $this->processForm($request, $this->buildForm());
    }
}
