<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class OfferController extends AbstractController
{
    private function buildForm($offerId, $offerManager)
    {
        $offer = $offerManager->find($offerId);
        $form = $this->createForm(
            new LineVersionType(),
            $offer,
            array(
                'action' => $this->generateUrl(
                    'tisseo_datawarehouse_offer_edit',
                    array(
                        'offerId' => $offerId
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $offerManager = $this->get('tisseo_datawarehouse.offer_manager');
        if ($form->isValid()) {
            $offerManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'offer.created',
                    array(),
                    'default'
                )
            );
            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_offer_list')
            );
        }
        return (null);
    }

    public function editAction(Request $request, $offerId)
    {
        $this->isGranted('BUSINESS_MANAGE_OFFER');
        $offerManager = $this->get('tisseo_datawarehouse.offer_manager');
        $form = $this->buildForm($offerId, $offerManager);
        $render = $this->processForm($request, $form);
        if (!$render) {
            return $this->render(
                'TisseoDatawarehouseBundle:Offer:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'title' => ($offerId ? 'offer.edit' : 'offer.create')
                )
            );
        }
        return ($render);
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_OFFER');
        $offerManager = $this->get('tisseo_datawarehouse.offer_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:Offer:list.html.twig',
            array(
                'pageTitle' => 'menu.offer_manage',
                'offers' => $offerManager->findActiveOffers()
            )
        );
    }
}
