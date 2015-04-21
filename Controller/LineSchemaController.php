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
     * Display the list view of all LineVersion.
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function listAction(Request $request)
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');

        /** @var \Tisseo\EndivBundle\Services\LineVersionManager $lineVersionManager */
        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

        $now = new \Datetime();

        return $this->render(
            'TisseoTidBundle:LineSchema:list.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'data' => $lineVersionManager->findActiveLineVersions($now, 'schematic', true)
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
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');

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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editSchemaAction($lineId, Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        /** @var \Tisseo\EndivBundle\Services\LineManager $line */
        $lineManager = $this->get('tisseo_endiv.line_manager');

        $line = $lineManager->find($lineId);
        if (empty($line)) {
            throw new \Exception('Line id not found');
        }

        $now = new \DateTime();
        $LineSchematic = new Schematic();
        $LineSchematic->setLine($line);
        $LineSchematic->setName('L'. $lineId . '_' . $now->format('Ymd'));
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
                $result = $schematicManager->save($LineSchematic);

                $this->addFlash(
                    (($result[0]) ? 'success' : 'danger'),
                    $this->get('translator')->trans($result[1], array(), 'default')
                );

                if ($result[0]) {
                    /** @var \Tisseo\EndivBundle\Services\LineVersionManager $lineVersionManager */
                    $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
                    $lineVersion = $lineVersionManager->findLastLineVersionOfLine(
                        $LineSchematic->getLine()->getId()
                    );
                    $lineVersion->setSchematic($LineSchematic);
                    $result = $lineVersionManager->save($lineVersion);

                    $this->addFlash(
                        (($result[0]) ? 'success' : 'danger'),
                        $this->get('translator')->trans($result[1], array(), 'default')
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
     */
    public function askSchemaAction($lineId, Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $form = $this->createForm(new MailType(),
            array(
                'to' => 'pierre.cl@gmail.com'
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
                $message = \Swift_Message::newInstance()
                    ->setSubject('Demande de nouveau schéma - LIGNE L' . $lineId)
                    ->setFrom('send@example.com')
                    ->setTo($data['to'])
                    ->setBody($data['body']);

                $this->get('mailer')->send($message);
                $this->addFlash('success', 'mailer.message.success');

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