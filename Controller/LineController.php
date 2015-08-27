<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\LineType;
use Tisseo\CoreBundle\Controller\CoreController;
use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineDatasource;
use Tisseo\EndivBundle\Services\LineManager;

class LineController extends CoreController
{
    /**
     * List
     *
     * Listing Lines
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE');
        
        return $this->render(
            'TisseoPaonBundle:Line:list.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.line',
                'pageTitle' => 'tisseo.paon.line.title.list',
                'lines' => $this->get('tisseo_endiv.line_manager')->findAllLinesByPriority()
            )
        );
    }

    /**
     * Edit
     * @param integer $lineId
     *
     * Creating/editing Line
     */
    public function editAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE');

        $lineManager = $this->get('tisseo_endiv.line_manager');
        $line = $lineManager->find($lineId);

        if (empty($line))
        {
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

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $lineManager->save($form->getData());
                $this->addFlash('success', ($lineId ? 'tisseo.flash.success.edited' : 'tisseo.flash.success.created'));
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_list');
        }

        return $this->render(
            'TisseoPaonBundle:Line:form.html.twig',
            array(
                'title' => ($lineId ? 'tisseo.paon.line.title.edit' : 'tisseo.paon.line.title.create'),
                'form' => $form->createView()
            )
        );
    }
}
