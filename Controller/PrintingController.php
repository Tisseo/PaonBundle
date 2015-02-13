<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tisseo\DatawarehouseBundle\Form\Type\PrintingType;
use Tisseo\DatawarehouseBundle\Entity\Printing;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class PrintingController extends AbstractController
{
    private function buildForm($printing)
    {
        $form = $this->createForm(
            new PrintingType(),
            $printing,
            array(
                'action' => $this->generateUrl(
                    'tisseo_datawarehouse_printing_create',
                    array(
                        'lineVersionId' => $printing->getLineVersion()->getId()
                    )
                ),
                'em' => $this->get('doctrine.orm.endiv_entity_manager')
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $printingManager = $this->get('tisseo_datawarehouse.printing_manager');
        if ($form->isValid()) {
            $printingManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'printing.created',
                    array(),
                    'default'
                )
            );
            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_line_version_list')
            );
        }
        return (null);
    }

    public function createAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (empty($lineVersion))
        {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans(
                    'line_version.not_found',
                    array(),
                    'default'
                )
            );

            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_line_version_list')
            );
        }
        else
        {
            $printing = new Printing($lineVersion);

            $form = $this->buildForm($printing);
            $render = $this->processForm($request, $form);

            if (!$render) {
                return $this->render(
                    'TisseoDatawarehouseBundle:Printing:form.html.twig',
                    array(
                        'form' => $form->createView(),
                        'title' => 'printing.create'
                    )
                );
            }

            return ($render);
        }
    }
}
