<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DataExchangeController extends AbstractController
{
    /**
     * Launch
     * @param string $jobName
     *
     * Launch a job.
     */
    public function launchAction($jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        // Check no master jobs are currently running and launch the job if it's clear
        if ($dataExchangeManager->getRunningJob() === null)
            $dataExchangeManager->launchJob($jobName);

        return $this->redirect($this->generateUrl('tisseo_paon_data_exchange_show'));
    }

    /**
     * Show
     *
     * Display list of runnable jobs.
     */
    public function showAction()
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        return $this->render(
            'TisseoPaonBundle:DataExchange:dataExchange.html.twig',
            array(
                'pageTitle' => 'menu.import_manage'
            )
        );
    }

    public function jobsAction()
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        $runningJobData = $dataExchangeManager->buildRunningJobData();

        return $this->render(
            'TisseoPaonBundle:DataExchange:jobs.html.twig',
            array(
                'jobs' => $dataExchangeManager->getJobsList(),
                'running_job' => $runningJobData,
                'running' => ($runningJobData === null ? false : true)
            )
        );
    }
}
