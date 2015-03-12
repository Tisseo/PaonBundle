<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DataExchangeController extends AbstractController
{
    public function editAction(Request $request, $jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');

        $dataExchangeManager = $this->get('tisseo_tid.data_exchange_manager');
        // Check no master jobs are currently running
        if ($dataExchangeManager->getRunningJob())
            return $this->redirect($this->generateUrl('tisseo_tid_data_exchange_import'));

        // Run the job
        $dataExchangeManager->launchJob($jobName);
        return $this->redirect($this->generateUrl('tisseo_tid_data_exchange_import'));
    }

    public function importAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');

        $dataExchangeManager = $this->get('tisseo_tid.data_exchange_manager');
        $runningJobData = $dataExchangeManager->buildRunningJobData();

        return $this->render(
            'TisseoTidBundle:DataExchange:imports.html.twig',
            array(
                'pageTitle' => 'menu.import_manage',
                'jobs' => $dataExchangeManager->getJobsList(),
                'running_job' => $runningJobData,
                'running' => ($runningJobData === null ? false : true)
            )
        );
    }
}
