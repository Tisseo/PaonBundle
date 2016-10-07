<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\LineVersionEditType;
use Tisseo\PaonBundle\Form\Type\LineVersionCreateType;
use Tisseo\PaonBundle\Form\Type\LineVersionCloseType;
use Tisseo\CoreBundle\Controller\CoreController;
use Tisseo\EndivBundle\Entity\LineVersion;
use Tisseo\EndivBundle\Entity\LineVersionDatasource;
use Tisseo\EndivBundle\Entity\Datasource;

class LineVersionController extends CoreController
{
    /**
     * Listing current/future versions of LineVersions
     */
    public function listAction()
    {
        $this->denyAccessUnlessGranted('BUSINESS_LIST_LINE_VERSION');

        return $this->render(
            'TisseoPaonBundle:LineVersion:list.html.twig',
            array(
                'navTitle'  => 'tisseo.paon.menu.line_version.manage',
                'pageTitle' => 'tisseo.paon.line_version.title.list',
                'data'      => $this->get('tisseo_endiv.manager.line_version')->findActiveLineVersions(true),
                'now'       => new \Datetime()
            )
        );
    }

    /**
     * Listing previous versions of LineVersions
     */
    public function historyAction()
    {
        $this->denyAccessUnlessGranted('BUSINESS_LIST_LINE_VERSION');

        return $this->render(
            'TisseoPaonBundle:LineVersion:history.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.line_version.manage',
                'pageTitle' => 'tisseo.paon.line_version.title.history',
                'lines' => $this->get('tisseo_endiv.manager.line')->findAllWithPastVersions(),
                'now' => new \Datetime()
            )
        );
    }

    /**
     * Create
     * @param integer $lineId
     *
     * Creating a new LineVersion
     */
    public function createAction(Request $request, $lineId = null)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        if ($lineId === null)
            $lineId = $request->request->get('lineId');

        $lineManager = $this->get('tisseo_endiv.manager.line');
        if (empty($lineId))
        {
            return $this->render(
                'TisseoPaonBundle:LineVersion:create.html.twig',
                array(
                    'title' => 'tisseo.paon.line_version.title.create',
                    'form' => null,
                    'lineVersion' => null,
                    'lines' => $lineManager->findAllLinesByPriority()
                )
            );
        }

        $minDate = null;
        $propertyManager = $this->get('tisseo_endiv.manager.property');

        $properties = $propertyManager->findAll();
        $line = $lineManager->find($lineId);
        $lastLineVersion = $line->getLastLineVersion();

        // no previous offer on this line
        if (empty($lastLineVersion)) {
            $lineVersion = new LineVersion($properties, null, $line);
        } else {
            $lineVersion = new LineVersion($properties, $lastLineVersion, null);
            $minDate = $lastLineVersion->getStartDate();
            $minDate->add(new \DateInterval('P1D'));
        }
        
        $modificationManager = $this->get('tisseo_endiv.manager.modification');
        $form = $this->createForm(
            new LineVersionCreateType($modificationManager, ($lineVersion->getLine() !== null ? $lineVersion->getLine()->getId() : null)),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_version_create',
                    array(
                        'lineId' => $lineVersion->getLine()->getId()
                    )
                ),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $lineVersion = $form->getData();
                $this->get('tisseo_endiv.manager.datasource')->fill(
                    $lineVersion,
                    Datasource::IV_SRC,
                    $this->getUser()->getUsername()
                );

                $this->get('tisseo_endiv.manager.line_version')->create($lineVersion);
                $this->addFlash('success', 'tisseo.flash.success.created');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:create.html.twig',
            array(
                'title' => 'tisseo.paon.line_version.title.create',
                'form' => $form->createView(),
                'lineVersion' => $lineVersion,
                'lines' => $lineManager->findAllLinesByPriority(),
                'minDate' => $minDate
            )
        );
    }

    /**
     * Edit
     * @param integer $lineVersionId
     *
     * Editing a LineVersion
     */
    public function editAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.manager.line_version');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (empty($lineVersion))
        {
            $this->addFlash('warning', 'tisseo.paon.line_version.message.not_found');
            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        // Update LineVersion -> LineVersionProperty -> Property relations
        // TODO: Looking at the Log table, it seems the synchronization leads to
        // a systematic UPDATE in database. Check if something better can be done.
        $properties = $this->get('tisseo_endiv.manager.property')->findAll();
        $lineVersion->synchronizeLineVersionProperties($properties);

        $form = $this->createForm(
            new LineVersionEditType(),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_version_edit',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                ),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $lineVersionManager->save($form->getData());
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:edit.html.twig',
            array(
                'title' => 'tisseo.paon.line_version.title.edit',
                'form' => $form->createView(),
                'lineVersion' => $lineVersion
            )
        );
    }

    /**
     * Close LineVersion
     * @param integer $lineVersionId
     *
     * Closing a LineVersion by setting its endDate.
     */
    public function closeAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.manager.line_version');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (empty($lineVersion))
        {
            $this->addFlash('warning', 'tisseo.paon.line_version.message.not_found');
            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        $form = $this->createForm(
            new LineVersionCloseType(),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_version_close',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                )
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $lineVersionManager->save($form->getData());
                $this->addFlash('success', 'tisseo.flash.success.created');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_version_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:close.html.twig',
            array(
                'title' => 'tisseo.paon.line_version.title.close',
                'form' => $form->createView(),
                'lineVersion' => $lineVersion
            )
        );
    }

    /**
     * Show
     * @param integer $lineVersionId
     *
     * Showing LineVersion informations
     */
    public function showAction(Request $request, $lineVersionId)
    {
        $this->denyAccessUnlessGranted(array(
            'BUSINESS_MANAGE_LINE_VERSION',
            'BUSINESS_LIST_LINE_VERSION'
        ));

        $history = false;
        $title = 'tisseo.paon.line_version.title.show';

        if ($request->isXmlHttpRequest())
        {
            $history = $request->get('history');
            if ($history)
                $title = 'tisseo.paon.line_version.title.show_history';
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:show.html.twig',
            array(
                'title' => $title,
                'history' => $history,
                'lineVersion' => $this->get('tisseo_endiv.manager.line_version')->find($lineVersionId)
            )
        );
    }

    /**
     * Clean
     * @param integer $lineVersionId
     *
     * Cleaning a LineVersion's timetable data from database.
     */
    public function cleanAction($lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        try
        {
            $this->get('tisseo_endiv.manager.stored_procedure')->cleanLineVersion($lineVersionId);
            $this->addFlash('success', 'tisseo.flash.success.cleaned');
        }
        catch (\Exception $e)
        {
            $this->addFlashException($e->getMessage());
        }

        return $this->redirectToRoute('tisseo_paon_line_version_list');
    }

    /**
     * Delete
     * @param integer $lineVersionId
     *
     * Deleting a LineVersion from database
     */
    public function deleteAction($lineVersionId)
    {
        $this->denyAccessUnlessGranted('BUSINESS_MANAGE_LINE_VERSION');

        try
        {
            $this->get('tisseo_endiv.manager.line_version')->delete($lineVersionId);
            $this->addFlash('success', 'tisseo.flash.success.deleted');
        }
        catch (\Exception $e)
        {
            $this->addFlashException($e->getMessage());
        }

        return $this->redirectToRoute('tisseo_paon_line_version_list');
    }
}
