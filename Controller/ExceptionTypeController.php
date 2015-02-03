<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ExceptionTypeController extends AbstractController
{
    private function buildForm($exceptionTypeId, $exceptionTypeManager)
    {
        $exceptionType = $exceptionTypeManager->find($exceptionTypeId);
        $form = $this->createForm(
            new LineVersionType(),
            $exceptionType,
            array(
                'action' => $this->generateUrl(
                    'tisseo_datawarehouse_exception_type_edit',
                    array(
                        'exceptionTypeId' => $exceptionTypeId
                    )
                )
            )
        );
        return ($form);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);
        $exceptionTypeManager = $this->get('tisseo_datawarehouse.exception_type_manager');
        if ($form->isValid()) {
            $exceptionTypeManager->save($form->getData());
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'exception_type.created',
                    array(),
                    'default'
                )
            );
            return $this->redirect(
                $this->generateUrl('tisseo_datawarehouse_exception_type_list')
            );
        }
        return (null);
    }

    public function editAction(Request $request, $exceptionTypeId)
    {
        $this->isGranted('BUSINESS_MANAGE_EXCEPTION');
        $exceptionTypeManager = $this->get('tisseo_datawarehouse.exception_type_manager');
        $form = $this->buildForm($exceptionTypeId, $exceptionTypeManager);
        $render = $this->processForm($request, $form);
        if (!$render) {
            return $this->render(
                'TisseoDatawarehouseBundle:ExceptionType:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'title' => ($exceptionTypeId ? 'exception_type.edit' : 'exception_type.create')
                )
            );
        }
        return ($render);
    }

    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_EXCEPTION');
        $exceptionTypeManager = $this->get('tisseo_datawarehouse.exception_type_manager');
        return $this->render(
            'TisseoDatawarehouseBundle:ExceptionType:list.html.twig',
            array(
                'pageTitle' => 'menu.exception_type_manage',
                'exceptions' => $exceptionTypeManager->findAll()
            )
        );
    }
}
