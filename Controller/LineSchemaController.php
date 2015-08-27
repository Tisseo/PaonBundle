<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\Entity\Schematic;
use Tisseo\PaonBundle\Form\Type\LineSchemaType;
use Tisseo\PaonBundle\Form\Type\MailType;
use Tisseo\PaonBundle\Entity\SchematicList;
use Tisseo\CoreBundle\Controller\CoreController;

class LineSchemaController extends CoreController
{
    /**
     * List
     *
     * Listing Lines and their LineSchematics
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:LineSchema:list.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.schematic.manage',
                'pageTitle' => 'tisseo.paon.line_schema.title.list',
                'data' => $this->get('tisseo_endiv.line_manager')->findAllLinesWithSchematic(true)
            )
        );
    }

    /**
     * List schema
     * @param integer $lineId
     *
     * Listing LineSchematic of a Line
     */
    public function listSchemaAction($lineId)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);

        return $this->render(
            'TisseoPaonBundle:LineSchema:listSchema.html.twig',
            array(
                'title' => 'tisseo.paon.line_schema.title.list_form',
                'line' => $line,
                'schematics' => $this->get('tisseo_endiv.schematic_manager')->findLineSchematics($lineId)
            )
        );
    }

    /**
     * Choice list schema
     * @param integer $lineId
     * @param integer $schematicId
     *
     * ?
     */
    public function choiceListSchemaAction($lineId, $schematicId = null)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:LineSchema:choiceListSchema.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'lineId' => $lineId,
                'schematicId' => $schematicId,
                'schematics' => $this->get('tisseo_endiv.schematic_manager')->findLineSchematics($lineId, 0, true)
            )
        );
    }

    /**
     * Edit schema
     * @param integer $lineId
     * @param boolean $addInfo
     *
     *
     */
    public function editSchemaAction(Request $request, $lineId, $addInfo)
    {
        $this->isGranted('BUSINESS_MANAGE_NEW_SCHEMA');

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        if (empty($line)) {
            throw new \Exception('Line id not found');
        }

        $lineSchematic = new Schematic();
        $lineSchematic->setLine($line);
        $lineSchematic->setDate(new \Datetime());

        $form = $this->createForm(
            new LineSchemaType(false, $addInfo),
            $lineSchematic,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_schema_edit',
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
                $lineSchematic = $form->getData();
                $lineSchematic->setName($line->getNumber() . '_' . $lineSchematic->getDate()->format('Ymd'));
                $this->get('tisseo_endiv.schematic_manager')->save($lineSchematic);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            $lineGroupGisContents = $this->get('tisseo_endiv.line_group_gis_content_manager')->findByLine($lineId);

            foreach ($lineGroupGisContents as $lineGroupGisContent)
                $this->addFlash('warning', $this->get('translator')->trans('line_schema.warning_group', array('%name%' => $lineGroupGisContent->getLineGroupGis()->getName())));

            return $this->redirectToRoute('tisseo_paon_line_schema_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineSchema:editSchemaForm.html.twig',
            array(
                'title' => 'line_schema.new',
                'form' => $form->createView(),
                'lineId' => $lineId,
            )
        );
    }

    /**
     * Ask schema
     * @param integer $lineId
     *
     * Sending a mail for a LineSchema deposit
     */
    public function askSchemaAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_ASK_SCHEMA');

        $form = $this->createForm(
            new MailType(),
            array(
                'to' => $this->container->getParameter('tisseo_paon.default_email_dest')
            ),
            array(
            'action' => $this->generateUrl(
                'tisseo_paon_schema_ask',
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
            $data = $form->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('tisseo.paon.line_schema.mail.object', array('%line%' => $line->getNumber())))
                ->setFrom($this->container->getParameter('tisseo_paon.default_email_exp'))
                ->setTo(explode(',', $data['to']))
                ->setBody($data['body']);

            $this->get('mailer')->send($message);
            $this->addFlash('success', 'tisseo.flash.success.sent');

            return $this->redirectToRoute('tisseo_paon_line_schema_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineSchema:askSchemaForm.html.twig',
            array(
                'title' => 'tisseo.paon.line_schema.title.new',
                'form' => $form->createView(),
                'lineNumber' => $line->getNumber()
            )
        );
    }

    /**
     * Deprecated schema
     * @param $lineId
     *
     * ?
     */
    public function deprecatedSchemaAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_NEW_SCHEMA');

        /** @var \Tisseo\EndivBundle\Services\SchematicManager $schematicManager */
        $schematicManager = $this->get('tisseo_endiv.schematic_manager');
        $schematics = $schematicManager->findLineSchematics($lineId, 0);

        $line = $this->get('tisseo_endiv.line_manager')->find($lineId);
        if (empty($line))
            throw new \Exception('Line id not found');

        $schematicList = new SchematicList();
        foreach($schematics as $schematic)
            $schematicList->setSchematics($schematic);

        $form = $this->createForm(
            'paon_list_schema',
            $schematicList,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_schema_deprecated',
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
                    $schematicManager->save($schematic);
                $this->addFlash('success', 'tisseo.flash.success.edited');
            }
            catch (\Exception $e)
            {
                $this->addFlashException($e->getMessage());
            }

            return $this->redirectToRoute('tisseo_paon_line_schema_list');
        }

        return $this->render(
            'TisseoPaonBundle:LineSchema:deprecatedSchemaForm.html.twig',
            array(
                'form' => $form->createView(),
                'schematics' => $schematics,
            )
        );
    }
}
