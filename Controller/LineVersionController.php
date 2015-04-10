<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\TidBundle\Form\Type\LineVersionType;
use Tisseo\EndivBundle\Entity\LineVersion;

class LineVersionController extends AbstractController
{
    /*
     * Build Form
     * @param LineVersion $lineVersion
     * @param boolean $new
     * @param boolean $secondStape
     * @param boolean $close
     * @param string $url
     * @return Form $form
     *
     * Build a new LineVersionType with different data switch booleans values.
     * This form is used by create/edit/close views and must handle different
     * data in each view.
     */
    private function buildForm(LineVersion $lineVersion, $new, $secondStape, $close = false, $url)
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

    /**
     * Process form
     * @param Request $request
     * @param Form $form
     * @param boolean $closure
     * @param boolean $create
     *
     * Process to form validation and launche different actions switch form
     * data.
     * Actions can be close, create, edit.
     */
    private function processForm(Request $request, $form, $create = false)
    {
        $form->handleRequest($request);
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        if ($form->isValid()) {
            if($create)
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
     * Edit
     * @param Request $request
     * @param integer $lineVersionId
     *
     * Handle Form display / Form validation for the edition view.
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
        $render = $this->processForm($request, $form, $new);

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

    /**
     * Close
     * @param Request $request
     * @param integer $lineVersionId
     *
     * Handle Form display / Form validation for the closing view.
     */
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
            $render = $this->processForm($request, $form);

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

    /**
     * Select by Line
     * @param Request $request
     *
     * Called through AJAX request, in order to display LineVersion using a
     * lineId. This action is called by LineVersion create view. The first
     * stape of this view is to choose the Line then display a new LineVersion
     * form related to this Line. This action handle the display of the second
     * stape.
     */
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
        $render = $this->processForm($request, $form, true);
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

    /**
     * List
     * @param Request $request
     *
     * Display the list view of all LineVersion.
     */
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

    /**
     * Show
     * @param Request $request
     * @param integer $lineVersionId
     *
     * Display a LineVersion in a view.
     */
    public function showAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $history = false;
        $title = 'line_version.show';

        if ($request->isXmlHttpRequest())
        {
            $history = $request->get('history');
            if ($history)
                $title = 'line_version.history.show';
        }

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        return $this->render(
            'TisseoTidBundle:LineVersion:show.html.twig',
            array(
                'title' => $title,
                'history' => $history,
                'lineVersion' => $lineVersionManager->find($lineVersionId)
            )
        );
    }

    /**
     * History
     * @param Request $request
     *
     * Display a list of LineVersion with all their previous versions.
     */
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

    /**
     * Clean
     * @param Request $request
     * @param integer $lineVersionId
     *
     * Launch a cleaning action in database for the related LineVersion.
     */
    public function cleanAction(Request $request, $lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');
        $storedProcedureManager = $this->get('tisseo_endiv.stored_procedure_manager');
        $result = $storedProcedureManager->cleanLineVersion($lineVersionId);
        $this->get('session')->getFlashBag()->add(
            ($result ? 'success' : 'danger'),
            $this->get('translator')->trans(
                ($result ? 'line_version.clean' : 'line_version_not_clean'),
                array(),
                'default'
            )
        );
        return $this->redirect(
            $this->generateUrl('tisseo_tid_line_version_list')
        );
    }
}
