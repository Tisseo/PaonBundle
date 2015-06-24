<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\LineType;
use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineDatasource;
use Tisseo\EndivBundle\Services\LineManager;

class LineController extends AbstractController
{
    /*
     * Build Form
     * @param integer $lineId
     * @param LineManager $lineManager
     * @return Form $form
     *
     * Build a new LineType form.
     */
    private function buildForm($lineId, LineManager $lineManager)
    {
        $line = $lineManager->find($lineId);
        if (empty($line)) {
            $line = new Line();
            $lineDatasource = new LineDatasource();
            $line->addLineDatasources($lineDatasource);
        }
        $form = $this->createForm(
            new LineType(),
            $line,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_edit',
                    array(
                        'lineId' => $lineId
                    )
                )
            )
        );
        return ($form);
    }

    /*
     * Process Form
     * @param Form $form
     *
     * If form is valid, save Line in database, then redirect to LineVersion
     * list view.
     * Else, return the actual form view with errors.
     */
    private function processForm($form)
    {
        $form->handleRequest($this->getRequest());
        $lineManager = $this->get('tisseo_endiv.line_manager');
        if ($form->isValid()) {
            $lineManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'line.created',
                    array(),
                    'default'
                )
            );
            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_list')
            );
        }
        return (null);
    }

    /**
     * Edit
     * @param integer $lineId
     *
     * Build a new form view or validate the sent form.
     */
    public function editAction($lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE');
        $lineManager = $this->get('tisseo_endiv.line_manager');
        $form = $this->buildForm($lineId, $lineManager);
        $render = $this->processForm($form);
        if (!$render) {
            return $this->render(
                'TisseoPaonBundle:Line:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'title' => ($lineId ? 'line.edit' : 'line.create')
                )
            );
        }
        return ($render);
    }

    /**
     * List
     *
     * Render the Lines list view.
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE');
        $lineManager = $this->get('tisseo_endiv.line_manager');
        return $this->render(
            'TisseoPaonBundle:Line:list.html.twig',
            array(
                'pageTitle' => 'menu.line_manage',
                'lines' => $lineManager->findAllLinesByPriority()
            )
        );
    }
}
