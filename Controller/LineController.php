<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\LineType;
use Tisseo\PaonBundle\Form\Type\LineStatusType;
use Tisseo\CoreBundle\Controller\CoreController;
use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineStatus;
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
        $this->denyAccessUnlessGranted('BUSINESS_LIST_LINE');

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
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE');

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

    /**
     * Validation list
     *
     * Listing Lines for validation
     */
    public function validationListAction()
    {
        $this->denyAccessUnlessGranted('BUSINESS_VALIDATE_LINES_EXPLOITATION');

        return $this->render(
            'TisseoPaonBundle:Line:validation_list.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.line_validation',
                'pageTitle' => 'tisseo.paon.line.title.list',
                'lines' => $this->get('tisseo_endiv.line_manager')->findByDataSource(1)
            )
        );
    }

    /**
     * Validate
     * @param integer $lineId
     * @param integer $suspend
     *
     * Validating Line
     */
    public function validateSuspendAction(Request $request, $lineId, $suspend)
    {
        $this->denyAccessUnlessGranted('BUSINESS_VALIDATE_LINES_EXPLOITATION');

        $lineManager = $this->get('tisseo_endiv.line_manager');
        $line = $lineManager->find($lineId);

        $lineStatus = new LineStatus();

        $form = $this->createForm(
            new LineStatusType(),
            $lineStatus,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_validate_suspend',
                    array(
                        'lineId' => $lineId,
                        'suspend' => $suspend
                    )
                ),
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $lineStatus = $form->getData();

                $lineStatus->setLine($line);
                $lineStatus->setDateTime(new \DateTime());
                $lineStatus->setLogin($this->get('security.token_storage')->getToken()->getUser()->getUsername());

                //if $suspend parameter is given, then the line gets suspended (status = 3), otherwise it gets validated (status = 1)
                $lineStatus->setStatus($suspend ? 3 : 1);

                $this->get('tisseo_endiv.line_status_manager')->save($lineStatus);

                if ($suspend)
                {
                    $message = \Swift_Message::newInstance()
                    ->setSubject($this->get('translator')->trans('tisseo.paon.line_status.mail.object', array('%line%' => $line->getNumber())))
                    ->setFrom($this->container->getParameter('tisseo_paon.default_email_exp'))
                    ->setTo($this->container->getParameter('tisseo_paon.line_validation.default_email_dest'))
                    ->setBody($this->get('translator')->trans('tisseo.paon.line_status.mail.body'));

                    $this->get('mailer')->send($message);
                }

                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_validation_list');
        }

        return $this->render(
            'TisseoPaonBundle:Line:validation_form.html.twig',
            array(
                'title' => ($suspend ? 'tisseo.paon.line_status.title.suspend' : 'tisseo.paon.line_status.title.validate'),
                'line' => $line,
                'form' => $form->createView(),
                'suspend' => $suspend
            )
        );
    }
}
