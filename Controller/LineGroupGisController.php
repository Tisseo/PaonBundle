<?php

namespace Tisseo\PaonBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineGroupGis;
use Tisseo\EndivBundle\Entity\LineGroupGisContent;
use Tisseo\PaonBundle\Form\Type\LineGroupGisType;


class LineGroupGisController extends AbstractController
{
    /**
     * List all line group gis
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_GROUP_GIS');

        /** @var \Tisseo\EndivBundle\Services\LineGroupGisManager $lineGroupGisManager */
        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');

        return $this->render(
            'TisseoPaonBundle:LineGroupGis:list.html.twig',
            array(
                'pageTitle' => 'line_group_gis.title',
                'groups' => $lineGroupGisManager->findAll()
            )
        );
    }

    /**
     * Create new line group gis
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request) {

        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        $lineGroupGis = new LineGroupGis();
        $lineGroupGisContent = new LineGroupGisContent();
        $lineGroupGis->getLineGroupGisContents()->add($lineGroupGisContent);

        $form = $this->createForm(new LineGroupGisType(), $lineGroupGis,
            array(
                'action' => $this->generateUrl('tisseo_paon_line_group_gis_new'),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var \Tisseo\EndivBundle\Services\LineGroupGisManager $lineGroupGisManager */
                $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');
                $result = $lineGroupGisManager->save($form->getData());

                $this->addFlash(
                    (($result[0]) ? 'success' : 'danger'),
                    $this->get('translator')->trans($result[1], array(), 'default')
                );

                return $this->redirect(
                    $this->generateUrl('tisseo_paon_line_group_gis_list')
                );

                //return $this->forward('TisseoPaonBundle:LineGroupGis:edit', array('id' => $result[2]->getId()));
            }
        }

        return $this->render(
            'TisseoPaonBundle:LineGroupGis:form.html.twig',
            array(
                'form' => $form->createView(),
                'is_new' => false,
                'title' => 'line_group_gis.form.title'
            )
        );
    }

    /**
     * Edit a line group gis
     *
     * @param integer $id line group GIS id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        /** @var \Tisseo\EndivBundle\Services\LineGroupGisManager $lineGroupGisManager */
        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');

        /** @var \Tisseo\EndivBundle\Entity\LineGroupGis $lineGroupGis */
        $lineGroupGis = $lineGroupGisManager->find($id);

        if (!$lineGroupGis) {
            throw $this->createNotFoundException('line_group_gis.not_found : ' . $id);
        }

        $form = $this->createForm(new LineGroupGisType(), $lineGroupGis,
            array(
                'action' => $this->generateUrl('tisseo_paon_line_group_gis_edit', array('id' => $id)),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $result = $lineGroupGisManager->save($form->getData());

                $this->addFlash(
                    (($result[0]) ? 'success' : 'danger'),
                    $this->get('translator')->trans($result[1], array(), 'default')
                );

                return $this->redirect(
                    $this->generateUrl('tisseo_paon_line_group_gis_list')
                );
            }
        }

       return $this->render(
            'TisseoPaonBundle:LineGroupGis:form.html.twig',
            array(
                'form' => $form->createView(),
                'is_new' => false,
                'title' => 'line_group_gis.form.title'
            )
        );
    }

    /**
     * Delete a line groupgis
     *
     * @param $id line group gis  id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id, Request $request)
    {
        /** @var \Tisseo\EndivBundle\Services\LineGroupGisManager $lineGroupGisManager */
        $lineGroupGisManager = $this->get('tisseo_endiv.line_group_gis_manager');

        /** @var \Tisseo\EndivBundle\Entity\LineGroupGis $lineGroupGis */
        $lineGroupGis = $lineGroupGisManager->find($id);

        if (!$lineGroupGis) {
            throw $this->createNotFoundException('line_group_gis.not_found : ' . $id);
        }

        $result = $lineGroupGisManager->remove($lineGroupGis);

        $this->addFlash(
            (($result[0]) ? 'success' : 'danger'),
            $this->get('translator')->trans($result[1], array(), 'default')
        );

        return $this->redirect(
            $this->generateUrl('tisseo_paon_line_group_gis_list')
        );
    }

}