<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\CoreBundle\Controller\CoreController;
use Tisseo\PaonBundle\Form\Type\PrintingType;
use Tisseo\EndivBundle\Entity\Printing;
use Symfony\Component\HttpFoundation\Response;

class PrintingController extends CoreController
{
    /**
     * Create
     *
     * @param int $lineVersionId
     *
     * Creating Printing
     * @return Response A Response instance
     */
    public function createAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (empty($lineVersion)) {
            $this->addFlash('warning', 'tisseo.paon.line_version.message.not_found');

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        $printing = new Printing();
        $printing->setLineVersion($lineVersion);

        $form = $this->createForm(
            new PrintingType(),
            $printing,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_printing_create',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                ),
                'em' => $this->get('doctrine.orm.endiv_entity_manager')
            )
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->get('tisseo_endiv.printing_manager')->save($form->getData());
                $this->addFlash('success', 'tisseo.flash.success.created');
            } catch (\Exception $e) {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        return $this->render(
            'TisseoPaonBundle:Printing:form.html.twig',
            array(
                'title' => 'tisseo.paon.printing.title.create',
                'form' => $form->createView()
            )
        );
    }
}
