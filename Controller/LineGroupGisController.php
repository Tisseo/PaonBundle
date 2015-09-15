<?php

namespace Tisseo\PaonBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineGroupGis;
use Tisseo\EndivBundle\Entity\LineGroupGisContent;
use Tisseo\PaonBundle\Form\Type\LineGroupGisType;
use Tisseo\CoreBundle\Controller\CoreController;

class LineGroupGisController extends CoreController
{
    /**
     * List
     *
     * Listing LineGroupGis
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_GROUP_GIS');

        return $this->render(
            'TisseoPaonBundle:LineGroupGis:list.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.schematic.manage',
                'pageTitle' => 'tisseo.paon.line_group_gis.title.list',
                'groups' => $this->get('tisseo_endiv.line_group_gis_manager')->findAll()
            )
        );
    }

    /**
     * Edit
     * @param integer $lineGroupGisId
     *
     * Editing LineGroupGis
     */
    public function editAction(Request $request, $lineGroupGisId)
    {
        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');
        $lineGroupGis = $lineGroupGisManager->find($lineGroupGisId);

        if (empty($lineGroupGis))
        {
            $lineGroupGis = new LineGroupGis();
            $lineGroupGisContent = new LineGroupGisContent();
            $lineGroupGis->getLineGroupGisContents()->add($lineGroupGisContent);
        }

        $form = $this->createForm(
            new LineGroupGisType(),
            $lineGroupGis,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_group_gis_edit',
                    array('lineGroupGisId' => $lineGroupGisId)
                ),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $lineGroupGisManager->save($form->getData());

                $schematics = array();
                foreach($form['LineGroupGisContents'] as $lineGroupContent)
                    $schematics[] = $lineGroupContent['schematic']->getData()->getId();
                $this->get('tisseo_endiv.schematic_manager')->updateGroupGis($schematics, true);

                $this->addFlash('success', (empty($lineGroupGisId) ? 'tisseo.flash.success.created' : 'tisseo.flash.success.edited'));
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_group_gis_list');
        }

       return $this->render(
            'TisseoPaonBundle:LineGroupGis:form.html.twig',
            array(
                'title' => 'tisseo.paon.line_group_gis.title.edit',
                'form' => $form->createView()
            )
        );
    }

    /**
     * Delete
     * @param $lineGroupGisId
     *
     * Deleting LineGroupGis
     */
    public function deleteAction($lineGroupGisId)
    {
        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');
        $lineGroupGis = $lineGroupGisManager->find($lineGroupGisId);

        if (empty($lineGroupGis))
            throw $this->createNotFoundException('Not found: '.$lineGroupGisId);

        try
        {
            $lineGroupGisManager->remove($lineGroupGis);
            $this->addFlash('success', 'tisseo.flash.success.deleted');
        }
        catch (\Exception $e)
        {
            $this->addFlashException($e->getMessage());
        }

        return $this->redirectToRoute('tisseo_paon_line_group_gis_list');
    }

    /**
     * Schematics
     * @param $lineId
     *
     * Returning available Schematics for a LineGroupGis
     */
    public function schematicsAction($lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        $response = new JsonResponse();
        $response->setData($line->getGisSchematics());

        return $response;
    }
}
