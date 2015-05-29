<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tisseo\PaonBundle\Form\Type\PrintingType;
use Tisseo\EndivBundle\Entity\Printing;
use Tisseo\EndivBundle\Entity\LineVersion;

class PrintingController extends AbstractController
{
    /*
     * Build Form
     * @param Printing $printing
     * @return Form $form
     *
     * Build a new PrintingType form.
     */
    private function buildForm(Printing $printing)
    {
        $form = $this->createForm(
            new PrintingType(),
            $printing,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_printing_create',
                    array(
                        'lineVersionId' => $printing->getLineVersion()->getId()
                    )
                ),
                'em' => $this->get('doctrine.orm.endiv_entity_manager')
            )
        );
        return ($form);
    }

    /**
     * Process form
     * @param Request $request
     * @param Form $form
     *
     * Handle Form display / Form validation.
     */
    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $printingManager = $this->get('tisseo_endiv.printing_manager');
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
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }
        return (null);
    }

    /**
     * Create
     * @param Request $request
     * @param integer $lineVersionId
     *
     * Display a new Printing form.
     */
    public function createAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
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
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }
        else
        {
            $printing = new Printing($lineVersion);

            $form = $this->buildForm($printing);
            $render = $this->processForm($request, $form);

            if (!$render) {
                return $this->render(
                    'TisseoPaonBundle:Printing:form.html.twig',
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
