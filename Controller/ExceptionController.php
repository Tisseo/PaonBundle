<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\CommentType;
use Tisseo\CoreBundle\Controller\CoreController;

class ExceptionController extends CoreController
{
    /**
     * Edit
     *
     * @param int $lineVersionId
     *
     * If request's method is GET, display a pseudo-form (ajax/json) which
     * purpose is to create/delete comments and link them to trips.
     *
     * Otherwise, the pseudo-form data is sent as AJAX POST request and is
     * decoded then will be used for database update.
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_EXCEPTION');

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $data = json_decode($request->getContent(), true);

            try {
                $this->get('tisseo_endiv.trip_manager')->updateComments($data);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            } catch (\Exception $e) {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        // GET pseudo-form view
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $gridCalendarManager = $this->get('tisseo_endiv.grid_calendar_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        return $this->render(
            'TisseoPaonBundle:Exception:edit.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.line_version.manage',
                'pageTitle' => 'tisseo.paon.exception.title.edit',
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
    public function commentAction(Request $request)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_EXCEPTION');

        $form = $this->createForm(
            new CommentType()
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            return $this->prepareJsonResponse(array(
                'label' => $form['label']->getData(),
                'commentText' => $form['commentText']->getData()
            ));
        }

        return $this->render(
            'TisseoPaonBundle:Comment:form.html.twig',
            array(
                'title' => 'tisseo.paon.comment.title.edit',
                'form' => $form->createView()
            )
        );
    }
}
