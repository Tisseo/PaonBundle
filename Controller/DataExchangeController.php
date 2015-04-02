<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DataExchangeController extends AbstractController
{
    /**
     * Launch
     * @param Request $request
     * @param string $jobName
     *
     * Launch a job.
     */
    public function launchAction(Request $request, $jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        $dataExchangeManager = $this->get('tisseo_tid.data_exchange_manager');
        // Check no master jobs are currently running and launch the job if it's clear
        if ($dataExchangeManager->getRunningJob() === null)
            $dataExchangeManager->launchJob($jobName);

        return $this->redirect($this->generateUrl('tisseo_tid_data_exchange_show'));
    }

    /**
     * Show
     * @param Request $request
     *
     * Display list of runnable jobs.
     */
    public function showAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        $dataExchangeManager = $this->get('tisseo_tid.data_exchange_manager');
        $runningJobData = $dataExchangeManager->buildRunningJobData();

        return $this->render(
            'TisseoTidBundle:DataExchange:jobs.html.twig',
            array(
                'pageTitle' => 'menu.import_manage',
                'jobs' => $dataExchangeManager->getJobsList(),
                'running_job' => $runningJobData,
                'running' => ($runningJobData === null ? false : true)
            )
        );
    }
}
