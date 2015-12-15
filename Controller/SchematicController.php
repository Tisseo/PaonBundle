<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Tisseo\EndivBundle\Entity\Schematic;
use Tisseo\PaonBundle\Entity\SchematicList;
use Tisseo\PaonBundle\Form\Type\SchematicType;
use Tisseo\PaonBundle\Form\Type\ListSchematicType;
use Tisseo\PaonBundle\Form\Type\MailType;
use Tisseo\CoreBundle\Controller\CoreController;

class SchematicController extends CoreController
{
    /**
     * List with lines
     *
     * Listing Lines and their Schematics
     */
    public function listWithLinesAction()
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:Schematic:list_with_lines.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.schematic.manage',
                'pageTitle' => 'tisseo.paon.schematic.title.list',
                'data' => $this->get('tisseo_endiv.line_manager')->findAllLinesWithSchematic(true)
            )
        );
    }

    /**
     * List schema
     * @param integer $lineId
     *
     * Listing Schematic of a Line
     */
    public function listAction($lineId)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);

        return $this->render(
            'TisseoPaonBundle:Schematic:list.html.twig',
            array(
                'title' => 'tisseo.paon.schematic.title.list_form',
                'line' => $line
            )
        );
    }

    /**
     * Export schemas
     *
     * choosing a start date for exporting schematics as csv
     */
    public function exportAction()
    {
        $this->isGranted('BUSINESS_MANAGE_NEW_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:Schematic:export.html.twig',
            array(
                'title' => 'tisseo.paon.schematic.title.export',
            )
        );
    }

    /**
     * Choice
     * @param integer $lineId
     * @param integer $schematicId
     *
     * Choosing a schematic from a list
     */
    public function choiceAction($lineId, $schematicId = null)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);

        return $this->render(
            'TisseoPaonBundle:Schematic:choice.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'line' => $line,
                'schematicId' => $schematicId
            )
        );
    }

    /**
     * Edit schema
     * @param integer $lineId
     * @param boolean $addInfo
     *
     * Uploading a Schematic
     */
    public function editAction(Request $request, $lineId, $addInfo)
    {
        $this->isGranted('BUSINESS_MANAGE_NEW_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        if (empty($line)) {
            throw new \Exception('Line id not found');
        }

        $schematic = new Schematic();
        $schematic->setLine($line);
        $schematic->setDate(new \Datetime());

        $form = $this->createForm(
            new SchematicType(false, $addInfo),
            $schematic,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_schematic_edit',
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
                $schematic = $form->getData();
                $schematic->setName($line->getNumber() . '_' . $schematic->getDate()->format('Ymd'));
                $this->get('tisseo_endiv.schematic_manager')->save($schematic);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            $lineGroupGisContents = $this->get('tisseo_endiv.line_group_gis_content_manager')->findByLine($lineId);

            foreach ($lineGroupGisContents as $lineGroupGisContent)
                $this->addFlash('warning', $this->get('translator')->trans('tisseo.paon.schematic.message.warning_group', array('%name%' => $lineGroupGisContent->getLineGroupGis()->getName())));

            return $this->redirectToRoute('tisseo_paon_schematic_list_with_lines');
        }

        return $this->render(
            'TisseoPaonBundle:Schematic:edit.html.twig',
            array(
                'title' => 'schematic.new',
                'form' => $form->createView(),
                'lineId' => $lineId,
            )
        );
    }

    /**
     * Ask
     * @param integer $lineId
     *
     * Sending a mail for a Schematic deposit
     */
    public function askAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_ASK_SCHEMA');

        $form = $this->createForm(
            new MailType(),
            array(
                'to' => $this->container->getParameter('tisseo_paon.default_email_dest')
            ),
            array(
            'action' => $this->generateUrl(
                'tisseo_paon_schematic_ask',
                array(
                    'lineId' => $lineId
                )
            )
        ));

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        if (empty($line)) {
            throw new \Exception('Line id not found');
        }

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $data = $form->getData();

                $message = \Swift_Message::newInstance()
                    ->setSubject($this->get('translator')->trans('tisseo.paon.schematic.mail.object', array('%line%' => $line->getNumber())))
                    ->setFrom($this->container->getParameter('tisseo_paon.default_email_exp'))
                    ->setTo(explode(',', $data['to']))
                    ->setBody($data['body']);

                $this->get('mailer')->send($message);
                $this->addFlash('success', 'tisseo.flash.success.sent');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_schematic_list_with_lines');
        }

        return $this->render(
            'TisseoPaonBundle:Schematic:ask.html.twig',
            array(
                'title' => 'tisseo.paon.schematic.title.new',
                'form' => $form->createView(),
                'lineNumber' => $line->getNumber()
            )
        );
    }

    /**
     * Deprecate
     * @param $lineId
     *
     * Setting a Schematic deprecated
     */
    public function deprecateAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_DEPRECATE_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        if (empty($line))
            throw new \Exception('Line id not found');

        $schematicList = new SchematicList();
        foreach($line->getFileSchematics() as $schematic)
            $schematicList->addSchematic($schematic);

        $form = $this->createForm(
            new ListSchematicType(true, false),
            $schematicList,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_schematic_deprecate',
                    array(
                        'lineId' => $lineId
                    )
                )
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $data = $form->getData();
            try
            {
                foreach ($data->schematics as $schematic)
                    $this->get('tisseo_endiv.schematic_manager')->save($schematic);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_schematic_list_with_lines');
        }

        return $this->render(
            'TisseoPaonBundle:Schematic:deprecate.html.twig',
            array(
                'form' => $form->createView(),
                'schematics' => $line->getSchematics()
            )
        );
    }

    /**
     * Delete
     * @param $lineId
     *
     * Deleting a Schematic
     */
    public function deleteAction(Request $request, $lineId, $schematicId = null)
    {
        $this->isGranted('BUSINESS_MANAGE_DELETE_SCHEMA');

        if ($request->isXmlHttpRequest() && $request->getMethod() === 'POST')
            $this->get('tisseo_endiv.schematic_manager')->remove($schematicId);

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);

        $schematics = new ArrayCollection();
        foreach($line->getSchematics() as $schematic)
        {
            if ($schematic->getLineVersions()->isEmpty())
                $schematics[] = $schematic;
        }

        return $this->render(
            'TisseoPaonBundle:Schematic:delete.html.twig',
            array(
                'line' => $line,
                'schematics' => $schematics
            )
        );
    }

    /**
     * Gis
     * @param $lineId
     *
     * Setting a Schematic's groupGis attribute
     */
    public function gisAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_GROUP_GIS');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);

        $schematicList = new SchematicList();
        foreach($line->getFileSchematics() as $schematic)
            $schematicList->addSchematic($schematic);

        $form = $this->createForm(
            new ListSchematicType(false, true),
            $schematicList,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_schematic_group_gis',
                    array(
                        'lineId' => $lineId
                    )
                )
            )
        );

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $data = $form->getData();
            try
            {
                foreach ($data->schematics as $schematic)
                    $this->get('tisseo_endiv.schematic_manager')->save($schematic);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_schematic_list_with_lines');
        }

        return $this->render(
            'TisseoPaonBundle:Schematic:group_gis.html.twig',
            array(
                'form' => $form->createView(),
                'schematics' => $line->getSchematics()
            )
        );
    }
}
