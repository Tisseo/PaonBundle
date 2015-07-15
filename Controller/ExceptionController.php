<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tisseo\PaonBundle\Form\Type\CommentType;

class ExceptionController extends AbstractController
{
    /*
     * Build Form
     * @return Form $form
     *
     * Build a new CommentType form.
     */
    private function buildForm()
    {
        $form = $this->createForm(
            new CommentType(),
            null,
            array()
        );
        return ($form);
    }

    /*
     * Process Form
     * @param Form $form
     *
     * If form is valid, return its data in JSON array.
     * Else, return the actual form with errors.
     */
    private function processForm($form)
    {
        $form->handleRequest($this->getRequest());
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
            'TisseoPaonBundle:Comment:form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'comment.create'
            )
        );
    }

    /**
     * Edit
     * @param integer $lineVersionId
     *
     * If request's method is GET, display a pseudo-form (ajax/json) which
     * purpose is to create/delete comments and link them to trips.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');
        $request = $this->getRequest();

        // POST data from pseudo-form
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
            $data = json_decode($request->getContent(), true);

            $tripManager = $this->get('tisseo_endiv.trip_manager');
            $tripManager->updateComments($data);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'exception.comments_updated',
                    array(),
                    'default'
                )
            );

            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }

        // GET pseudo-form view
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        return $this->render(
            'TisseoPaonBundle:Exception:edit.html.twig',
            array(
                'pageTitle' => 'menu.exception_manage',
                'lineVersion' => $lineVersion,
                'data' => $gridCalendarManager->findRelatedTrips($lineVersion->getGridCalendars())
            )
        );
    }

    /*
     * Comment
     *
     * Render a new CommentType form
     */
    public function commentAction()
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');
        return $this->processForm($this->buildForm());
    }
}
