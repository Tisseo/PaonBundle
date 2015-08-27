<?php

namespace Tisseo\PaonBundle\Controller;

use Tisseo\CoreBundle\Controller\CoreController;

class DataExchangeController extends CoreController
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

        return $this->redirectToRoute('tisseo_paon_data_exchange_show');
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
                'navTitle' => 'tisseo.paon.menu.data_exchange',
                'pageTitle' => 'tisseo.paon.data_exchange.title.show'
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
                'runningJob' => $runningJobData,
                'running' => ($runningJobData === null ? false : true)
            )
        );
    }
}
