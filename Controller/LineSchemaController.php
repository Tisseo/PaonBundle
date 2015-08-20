<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\Entity\Schematic;
use Tisseo\PaonBundle\Form\Type\LineSchemaType;
use Tisseo\PaonBundle\Form\Type\MailType;
use Tisseo\PaonBundle\Entity\SchematicList;

class LineSchemaController extends AbstractController
{
    /**
     * List
     *
     * Display the list view of all line.
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:LineSchema:list.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'data' => $this->get('tisseo_endiv.line_manager')->findAllLinesWithSchematic(true)
            )
        );
    }

    /**
     * @param integer $lineId
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function listSchemaAction($lineId)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        return $this->render(
            'TisseoPaonBundle:LineSchema:listSchema.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'lineId' => $lineId,
                'schematics' => $this->get('tisseo_endiv.schematic_manager')->findLineSchematics($lineId)
            )
        );
    }

    /**
     * @param integer $lineId
     * @param integer $schematicId
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
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
     * @param integer $lineId
     * @param boolean $addInfo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
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
            $lineSchematic = $form->getData();
            $lineSchematic->setName($line->getNumber() . '_' . $lineSchematic->getDate()->format('Ymd'));

            list($schematic, $message, $error) = $this->get('tisseo_endiv.schematic_manager')->save($lineSchematic);
            $this->addFlash(
                ($error === null ? 'success' : 'danger'),
                $this->get('translator')->trans($message, array('%error%' => $error), 'default')
            );

            $lineGroupGisContents = $this->get('tisseo_endiv.line_group_gis_content_manager')->findByLine($schematic->getLine()->getId());
            foreach ($lineGroupGisContents as $lineGroupGisContent) {
                $this->addFlash(
                    'warning',
                    $this->get('translator')->trans('line_schema.warning_group', array('%name%' => $lineGroupGisContent->getLineGroupGis()->getName()), 'default')
                );
            }

            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_schema_list')
            );
        }

        return $this->render(
            'TisseoPaonBundle:LineSchema:editSchemaForm.html.twig',
            array(
                'form' => $form->createView(),
                'lineId' => $lineId,
                'title' => 'line_schema.new'
            )
        );

    }

    /**
     * @param integer $lineId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function askSchemaAction(Request $request, $lineId)
    {
        $this->isGranted('BUSINESS_MANAGE_ASK_SCHEMA');

        $form = $this->createForm(new MailType(),
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
                ->setSubject('Demande de nouveau schéma - LIGNE ' . $line->getNumber())
                ->setFrom($this->container->getParameter('tisseo_paon.default_email_exp'))
                ->setTo(explode(',', $data['to']))
                ->setBody($data['body']);

            $this->get('mailer')->send($message);
            $this->addFlash(
                'success',
                $this->get('translator')->trans('mailer.schematic.confirm.success', array(), 'messages')
            );

            return $this->redirect($this->generateUrl(tisseo_paon_line_schema_list));
        }

        return $this->render(
            'TisseoPaonBundle:LineSchema:askSchemaForm.html.twig',
            array(
                'form' => $form->createView(),
                'lineNumber' => $line->getNumber(),
                'title' => 'Nouveau schema de la ligne '
            )
        );
    }

    /**
     * @param $lineId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
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
            foreach ($data->schematics as $schematic)
            {
                list($schematic, $message, $error) = $schematicManager->save($schematic);
                $this->addFlash(
                    ($error === null ? 'success' : 'danger'),
                    $this->get('translator')->trans($message, array('%error%' => $error), 'default')
                );
                if ($error !== null)
                    break;
            }
            return $this->redirect($this->generateUrl('tisseo_paon_line_schema_list'));
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
