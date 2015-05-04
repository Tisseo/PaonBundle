<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\Entity\Schematic;
use Tisseo\TidBundle\Form\Type\LineSchemaType;
use Tisseo\TidBundle\Form\Type\MailType;


class LineSchemaController extends AbstractController
{
    /**
     * List
     * @param Request $request
     *
     * Display the list view of all line.
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        /** @var \Tisseo\EndivBundle\Services\LineManager $lineManager */
        $lineManager = $this->get('tisseo_endiv.line_manager');

        return $this->render(
            'TisseoTidBundle:LineSchema:list.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'data' => $lineManager->findAllLinesWithSchematic(true)
            )
        );
    }

    /**
     * @param integer $lineId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function listSchemaAction($lineId, Request $request)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        /** @var \Tisseo\EndivBundle\Services\SchematicManager $schematicManager */
        $schematicManager = $this->get('tisseo_endiv.schematic_manager');

        return $this->render(
            'TisseoTidBundle:LineSchema:listSchema.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'lineId' => $lineId,
                'schematics' => $schematicManager->findLineSchematics($lineId)
            )
        );
    }

    /**
     * @param integer $lineId
     * @param integer $schematicId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function choiceListSchemaAction($lineId, $schematicId = null, Request $request)
    {
        $this->isGranted('BUSINESS_LIST_SCHEMA');

        /** @var \Tisseo\EndivBundle\Services\SchematicManager $schematicManager */
        $schematicManager = $this->get('tisseo_endiv.schematic_manager');

        return $this->render(
            'TisseoTidBundle:LineSchema:choiceListSchema.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'lineId' => $lineId,
                'schematicId' => $schematicId,
                'schematics' => $schematicManager->findLineSchematics($lineId)
            )
        );



    }

    /**
     * @param integer $lineId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editSchemaAction($lineId, Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_NEW_SCHEMA');

        /** @var \Tisseo\EndivBundle\Services\LineManager $line */
        $lineManager = $this->get('tisseo_endiv.line_manager');

        $line = $lineManager->find($lineId);
        if (empty($line)) {
            throw new \Exception('Line id not found');
        }

        $now = new \DateTime();
        $LineSchematic = new Schematic();
        $LineSchematic->setLine($line);
        $LineSchematic->setName($line->getNumber() . '_' . $now->format('Ymd'));
        $LineSchematic->setDate($now);

        $form = $this->createForm(new LineSchemaType(), $LineSchematic,
            array(
                'action' => $this->generateUrl(
                    'tisseo_tid_schema_edit',
                    array(
                        'lineId' => $lineId
                    )
                )
            )
        );

        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var \Tisseo\EndivBundle\Services\SchematicManager $schematicManager */
                $schematicManager = $this->get('tisseo_endiv.schematic_manager');

                /** @var \Tisseo\EndivBundle\Services\LineGroupGisContentManager $lineGroupGisContentManager */
                $lineGroupGisContentManager = $this->get('tisseo_endiv.line_group_gis_content_manager');

                $result = $schematicManager->save($LineSchematic);

                $this->addFlash(
                    (($result[0]) ? 'success' : 'danger'),
                    $this->get('translator')->trans($result[1], array(), 'default')
                );

                $result = $lineGroupGisContentManager->findLineGroup($result[2]->getLine()->getId());
                if (!empty($result)) {
                    $this->addFlash(
                        'warning',
                        $this->get('translator')->trans('line_schema.warning_group', array(), 'default')
                    );
                }


                return $this->redirect(
                    $this->generateUrl('tisseo_tid_line_schema_list')
                );
            }
        }

        return $this->render(
            'TisseoTidBundle:LineSchema:editSchemaForm.html.twig',
            array(
                'form' => $form->createView(),
                'lineId' => $lineId,
                'title' => 'Nouveau schema de la ligne '
            )
        );

    }

    /**
     * @param integer $lineId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function askSchemaAction($lineId, Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_ASK_SCHEMA');

        $form = $this->createForm(new MailType(),
            array(
                'to' => $this->container->getParameter('tisseo_tid.default_email_dest')
            ),
            array(
            'action' => $this->generateUrl(
                'tisseo_tid_schema_ask',
                array(
                    'lineId' => $lineId
                )
            )
        ));

        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                /** @var \Tisseo\EndivBundle\Services\LineManager $line */
                $lineManager = $this->get('tisseo_endiv.line_manager');
                $line = $lineManager->find($lineId);
                if (empty($line)) {
                    throw new \Exception('Line id not found');
                }

                $message = \Swift_Message::newInstance()
                    ->setSubject('Demande de nouveau schéma - LIGNE ' . $line->getNumber())
                    ->setFrom($this->container->getParameter('tisseo_tid.default_email_exp'))
                    ->setTo(explode(',', $data['to']))
                    ->setBody($data['body']);

                $this->get('mailer')->send($message);
                $this->addFlash('success', 'mailer.schematic.confirm.success');

                return $this->redirect($this->generateUrl(tisseo_tid_line_schema_list));
            }
        }

        return $this->render(
            'TisseoTidBundle:LineSchema:askSchemaForm.html.twig',
            array(
                'form' => $form->createView(),
                'lineId' => $lineId,
                'title' => 'Nouveau schema de la ligne '
            )
        );
    }
}