<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\TidBundle\Form\Type\LineVersionType;
use Tisseo\EndivBundle\Entity\LineVersion;

class LineVersionController extends AbstractController
{
    private function buildForm($lineVersion, $new, $secondStape, $close = false, $url)
    {
        $form = $this->createForm(
            new LineVersionType($lineVersion, $new, $secondStape, $close),
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

    private function processForm(Request $request, $form, $closure = false, $create = false)
    {
        $form->handleRequest($request);
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        if ($form->isValid()) {            
            if ($closure)
                $write = $lineVersionManager->close($form->getData());
            else if($create)
                $write = $lineVersionManager->create($form->getData());
            else
                $write = $lineVersionManager->save($form->getData());

            $this->get('session')->getFlashBag()->add(
                ($write[0] ? 'success' : 'danger'),
                $this->get('translator')->trans(
                    $write[1],
                    array(),
                    'default'
                )
            );

            return $this->redirect(
                $this->generateUrl('tisseo_tid_line_version_list')
            );
        }
        return (null);
    }

    /**
     *
     */
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
            $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
            $lineVersion = $lineVersionManager->find($lineVersionId);
            $new = false;
        }
        $form = $this->buildForm($lineVersion, $new, false, false, 'tisseo_tid_line_version_edit');
        $render = $this->processForm($request, $form, false, $new);

        if (!$render) {
            return $this->render(
                'TisseoTidBundle:LineVersion:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'new' => $new,
                    'stape' => false,
                    'lineVersion' => $lineVersion,
                    'title' => ($lineVersionId ? 'line_version.edit' : 'line_version.create')
                )
            );
        }

        return ($render);
    }

    public function closeAction(Request $request, $lineVersionId)
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
                $this->generateUrl('tisseo_tid_line_version_list')
            );
        }
        else
        {
            $form = $this->buildForm($lineVersion, false, false, true, 'tisseo_tid_line_version_close');
            $render = $this->processForm($request, $form, true, false);

            if (!$render) {
                return $this->render(
                    'TisseoTidBundle:LineVersion:form.html.twig',
                    array(
                        'form' => $form->createView(),
                        'new' => false,
                        'stape' => false,
                        'close' => true,
                        'title' => 'line_version.closure'
                    )
                );
            }
            return ($render);
        }
    }

    public function selectByLineAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineId = $request->request->get('line_id');
        if (!empty($lineId))
        {
            $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
            $lineVersionResult = $lineVersionManager->findLastLineVersionOfLine($lineId);
            if (empty($lineVersionResult))
            {
                $lineManager = $this->get('tisseo_endiv.line_manager');
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

        $form = $this->buildForm($lineVersion, true, true, false, 'tisseo_tid_select_line_version_by_line');
        $render = $this->processForm($request, $form, false, true);
        if (!$render)
        {
            return $this->render(
                'TisseoTidBundle:LineVersion:form.html.twig',
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

    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $now = new \Datetime();
        return $this->render(
            'TisseoTidBundle:LineVersion:list.html.twig',
            array(
                'pageTitle' => 'menu.line_version_active',
                'data' => $lineVersionManager->findActiveLineVersions($now, 'physicalMode')
            )
        );
    }

    public function consultAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $history = false;
        $title = 'line_version.consult';

        if ($request->isXmlHttpRequest())
        {
            $history = $request->get('history');
            if ($history)
                $title = 'line_version.history.consult';
        }

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        return $this->render(
            'TisseoTidBundle:LineVersion:consult.html.twig',
            array(
                'title' => $title,
                'history' => $history,
                'lineVersion' => $lineVersionManager->find($lineVersionId)
            )
        );
    }

    public function historyAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');
        $lineManager = $this->get('tisseo_endiv.line_manager');
        $lines = $lineManager->findAllLinesByPriority();
        return $this->render(
            'TisseoTidBundle:LineVersion:history.html.twig',
            array(
                'pageTitle' => 'menu.line_version_history',
                'lines' => $lines
            )
        );
    }

    public function purgeAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        if ($lineVersionManager->purge($lineVersionId))
        {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'line_version.purge',
                    array(),
                    'default'
                )
            );
        }
        else
        {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans(
                    'line_version.not_purge',
                    array(),
                    'default'
                )
            );
        }
        return $this->redirect(
            $this->generateUrl('tisseo_tid_line_version_list')
        );
    }
}
