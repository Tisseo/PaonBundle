<?php

namespace Tisseo\PaonBundle\Controller;

use Tisseo\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;
use Tisseo\EndivBundle\TisseoEndivBundle;

class DataExchangeController extends CoreController
{
    /**
     * Launch
     * @param string $jobName
     *
     * Launch a job.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function launchAction(Request $request, $jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_DATA_EXCHANGE');

        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        // Check no master jobs are currently running and launch the job if it's clear
        if ($dataExchangeManager->getRunningJob() === null) {

            if ($request->getMethod() == 'POST') {
                $data =  json_decode($request->getContent(), true);
                $params = $dataExchangeManager->buildRequestParam($jobName, $data);
            } else {
                $params = array();
            }

            $dataExchangeManager->launchJob($jobName, $params);
        }


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

    public function linesAction()
    {
        $lines = $this->get('tisseo_endiv.line_manager')->findByDataSource(1);

        return $this->render(
            'TisseoPaonBundle:DataExchange:lines.html.twig',
            array(
                'lines' => $lines
            )
        );
    }
}
