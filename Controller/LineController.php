<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\DatawarehouseBundle\Form\Type\LineType;
use Tisseo\DatawarehouseBundle\Entity\Line;
use Tisseo\DatawarehouseBundle\Entity\LineDatasource;

class LineController extends AbstractController
{
    private function buildForm($lineId, $lineManager)
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
                    'tisseo_datawarehouse_line_edit',
                    array(
                        'lineId' => $lineId
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $lineManager = $this->get('tisseo_datawarehouse.line_manager');
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
                $this->generateUrl('tisseo_datawarehouse_line_list')
            );
        }
        return (null);
    }

    public function editAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE');
        $lineManager = $this->get('tisseo_datawarehouse.line_manager');
        $form = $this->buildForm($lineId, $lineManager);
        $render = $this->processForm($request, $form);
        if (!$render) {
            return $this->render(
                'TisseoDatawarehouseBundle:Line:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'title' => ($lineId ? 'line.edit' : 'line.create')
                )
            );
        }
        return ($render);
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE');
        $lineManager = $this->get('tisseo_datawarehouse.line_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:Line:list.html.twig',
            array(
                'pageTitle' => 'menu.line_manage',
                'lines' => $lineManager->findAllLinesByPriority()
            )
        );
    }
}
