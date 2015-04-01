<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
     * purpose is to create/delete comments and link them to trips.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');

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
                $this->generateUrl('tisseo_tid_line_version_list')
            );
        }

        // GET pseudo-form view
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
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
