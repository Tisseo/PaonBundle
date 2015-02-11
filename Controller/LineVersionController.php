<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tisseo\DatawarehouseBundle\Form\Type\LineVersionType;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class LineVersionController extends AbstractController
{
    private function buildForm($lineVersion, $new, $secondStape, $url)
    {
        $form = $this->createForm(
            new LineVersionType($lineVersion, $new, $secondStape),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    $url,
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        if ($form->isValid()) {
            $lineVersionManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'line_version.created',
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

    public function editAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        if (empty($lineVersionId))
        {
            $lineVersion = new LineVersion();
            $new = true;
        }
        else
        {
            $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
            $lineVersion = $lineVersionManager->find($lineVersionId);
            $new = false;
        }
        $form = $this->buildForm($lineVersion, $new, false, 'tisseo_datawarehouse_line_version_edit');
        $render = $this->processForm($request, $form);

        if (!$render) {
            return $this->render(
                'TisseoDatawarehouseBundle:LineVersion:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'new' => $new,
                    'stape' => false,
                    'title' => ($lineVersionId ? 'line_version.edit' : 'line_version.create')
                )
            );
        }

        return ($render);
    }

    public function selectByLineAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');
        
        $lineId = $request->request->get('line_id');
        if (!empty($lineId))
        {
            $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
            $lineVersionResult = $lineVersionManager->findLineVersionByLine($lineId);
            if (empty($lineVersionResult))
            {
                $lineManager = $this->get('tisseo_datawarehouse.line_manager');
                $line = $lineManager->find($lineId);
                $lineVersion = new LineVersion(null, $line);
            }
            else
            {
                $lineVersion = new LineVersion($lineVersionResult);
            }
        }
        else
        {
            $lineVersion = new LineVersion();
        }

        $form = $this->buildForm($lineVersion, true, true, 'tisseo_datawarehouse_select_line_version_by_line');
        $render = $this->processForm($request, $form);
        if (!$render)
        {
            return $this->render(
                'TisseoDatawarehouseBundle:LineVersion:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'new' => true,
                    'stape' => true,
                    'title' => ('line_version.create')
                )
            );
        }
        return($render);
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');
        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        $now = new \Datetime();
        return $this->render(
            'TisseoDatawarehouseBundle:LineVersion:list.html.twig',
            array(
                'pageTitle' => 'menu.line_version_manage',
                'lineVersions' => $lineVersionManager->findActiveLineVersions($now)
            )
        );
    }
    
    public function consultAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');
        $lineVersionManager = $this->get('tisseo_datawarehouse.line_version_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:LineVersion:consult.html.twig',
            array(
                'title' => 'line_version.consult',
                'lineVersion' => $lineVersionManager->find($lineVersionId)
            )
        );
    }
}
