<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
        $this->denyAccessUnlessGranted(array(
            'BUSINESS_MANAGE_DATA_EXCHANGE',
            'BUSINESS_MANAGE_DATA_EXCHANGE_ROOT'
        ));

        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        // Check no master jobs are currently running and launch the job if it's clear
        if ($dataExchangeManager->getRunningJob() === null) {

            if ($request->getMethod() == 'POST') {
                $data =  json_decode($request->getContent(), true);
                $params = $dataExchangeManager->buildRequestParam($jobName, $data);
            } else {
                $params = array();
            }
            $role = $this->getJenkinsRole($dataExchangeManager);
            $dataExchangeManager->launchJob($jobName, $params, $role);
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
        $this->denyAccessUnlessGranted(array(
                'BUSINESS_MANAGE_DATA_EXCHANGE',
                'BUSINESS_MANAGE_DATA_EXCHANGE_ROOT'
        ));

        return $this->render(
            'TisseoPaonBundle:DataExchange:dataExchange.html.twig',
            array(
                'navTitle' => 'tisseo.paon.menu.data_exchange',
                'pageTitle' => 'tisseo.paon.data_exchange.title.show'
            )
        );
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function jobsAction()
    {
        $this->denyAccessUnlessGranted(array(
            'BUSINESS_MANAGE_DATA_EXCHANGE',
            'BUSINESS_MANAGE_DATA_EXCHANGE_ROOT'
        ));

        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        $runningJobData = $dataExchangeManager->buildRunningJobData();

        $role = $this->getJenkinsRole($dataExchangeManager);

        return $this->render(
            'TisseoPaonBundle:DataExchange:jobs.html.twig',
            array(
                'jobs' => $dataExchangeManager->getJobsList($role),
                'runningJob' => $runningJobData,
                'running' => ($runningJobData === null ? false : true)
            )
        );
    }

    /**
     * Gets the lines to select for Import FH task
     *
     * @return Response
     * @throws \Exception
     */
    public function linesAction()
    {
        $this->denyAccessUnlessGranted(array(
            'BUSINESS_MANAGE_DATA_EXCHANGE',
            'BUSINESS_MANAGE_DATA_EXCHANGE_ROOT'
        ));

        /**
         * TODO : Very ugly, if there is not a task with a specified name, then the method return an empty response
         * TODO : List of tasks depends of current user profile.
         * TODO : Need to refactor source code
        */
        $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        $role = $this->getJenkinsRole($dataExchangeManager);
        $jobs = $dataExchangeManager->getJobsList($role);
        if ($jobs[(count($jobs)-1)]['name'] != 'Import FH') {
            return new Response();
        }

        $lines = $this->get('tisseo_endiv.line_manager')->findByDataSourceSortByStatus(1);

        return $this->render(
            'TisseoPaonBundle:DataExchange:lines.html.twig',
            array(
                'lines' => $lines
            )
        );
    }

    /**
     * Returns the role who must be used to launch task
     *
     * @param null $dataExchangeManager instance of tisseo data exchange manager
     * @return string
     * @throws \Exception
     */
    private function getJenkinsRole($dataExchangeManager = null)
    {
        if (is_null($dataExchangeManager)) {
            $dataExchangeManager = $this->get('tisseo_paon.data_exchange_manager');
        }

        try {
            $this->denyAccessUnlessGranted('BUSINESS_MANAGE_DATA_EXCHANGE_ROOT');
            $role = $dataExchangeManager::ROLE_ADMIN;
        } catch(\Exception $e) {
            if ($e instanceof AccessDeniedException) {
                $role = $dataExchangeManager::ROLE_IV;
            } else {
                throw new \Exception($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $role;
    }
}
