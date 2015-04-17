<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;


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

        /** @var \Tisseo\EndivBundle\Services\LineVersionManager $lineVersionManager */
        $lineVersionManager = $this->get('tisseo_endiv.schematic_manager');

        return $this->render(
            'TisseoTidBundle:LineSchema:listSchema.html.twig',
            array(
                'pageTitle' => 'menu.schema_manage',
                'schematics' => $lineVersionManager->findLineSchematics($lineId)
            )
        );
    }
}