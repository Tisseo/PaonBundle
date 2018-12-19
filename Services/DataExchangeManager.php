<?php

namespace Tisseo\PaonBundle\Services;

class DataExchangeManager
{
    private $jenkinsServer;
    private $jenkinsUsers;
    private $masterJob;
    private $atomicJob;

    /**
     * @const string ROLE_ADMIN
     */
    const ROLE_ADMIN = 'admin';

    /**
     * @const string ROLE_IV
     */
    const ROLE_IV = 'iv';

    public function __construct($jenkinsServer, $jenkinsUsers, $masterJob, $atomicJob)
    {
        $this->jenkinsServer = $jenkinsServer;
        $this->jenkinsUsers = $jenkinsUsers;
        $this->masterJob = $masterJob;
        $this->atomicJob = $atomicJob;
    }

    private function chooseJenkinsUser($role)
    {
        foreach ($this->jenkinsUsers as $user) {
            if ($user['profile'] == $role) {
                return $user['user'];
            }
        }

        throw new \Exception('tisseo.paon.jenkins.user_not_found', 500);
    }

    /**
     * Request Jenkins
     *
     * @param string $url
     * @param bool   $returnJson
     * @param string $role
     * @param array  $params
     *
     * Call Jenkins by generating a curl request.
     * If $returnJson is true, return the response data as JSON.
     *
     * @return mixed
     */
    private function callJenkins($url, $returnJson = false, $role = self::ROLE_ADMIN, $params = array())
    {
        $jenkinsUser = $this->chooseJenkinsUser($role);
        $request = curl_init();
        curl_setopt($request, CURLINFO_HEADER_OUT, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_USERPWD, $jenkinsUser);

        if (count($params) > 0) {
            curl_setopt($request, CURLOPT_POST, count(http_build_query($params)));
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($request, CURLOPT_URL, $this->jenkinsServer.$url);

        $response = curl_exec($request);
        curl_close($request);

        $data = null;
        if ($returnJson) {
            $data = json_decode($response, true);
        }

        return $data;
    }

    /**
     * Get running job
     *
     * Check a job is running or not.
     */
    public function getRunningJob()
    {
        $url = 'view/IV%20-%20FLUX%20DE%20DONNEE/api/json?tree=jobs[name,color,lastBuild[number]]';
        $jsonData = $this->callJenkins($url, true, self::ROLE_ADMIN);

        // search for master job running
        if (is_array($jsonData) && count($jsonData) > 0) {
            foreach ($jsonData['jobs'] as $key => $val) {
                if (strpos($val['name'], $this->masterJob) !== false && strpos($val['color'], '_anime') !== false) {
                    return $val;
                }
            }
            // if no master job found, search for any running job
            foreach ($jsonData['jobs'] as $key => $val) {
                if (strpos($val['color'], '_anime') !== false) {
                    return $val;
                }
            }
        }
        // no running job (in view "FLUX DE DONNEES")
        return null;
    }

    /**
     * Get launcher
     *
     * @param string $jobName
     * @param string $number
     *
     * Get the launcher of a specific job.
     */
    public function getLauncher($jobName, $number)
    {
        $url = 'job/'.str_replace(' ', '%20', $jobName).'/'.$number.'/api/json?tree=actions[causes[userName,upstreamProject]],building';
        $jsonData = $this->callJenkins($url, true, self::ROLE_ADMIN);

        foreach ($jsonData['actions'] as $key => $action) {
            if (is_array($action) && count($action) > 0) {
                if (array_key_exists('userName', $action['causes'][0])) {
                    return $action['causes'][0]['userName'];
                }

                if (array_key_exists('upstreamProject', $action['causes'][0])) {
                    return str_replace($this->masterJob, '', str_replace($this->atomicJob, '', $action['causes'][0]['upstreamProject']));
                }
            }
        }

        return '';
    }

    /**
     * Launch job
     *
     * @param string $jobName
     * @param array  $params
     *
     * Launch a specific job.
     */
    public function launchJob($jobName, $params = array(), $role = self::ROLE_ADMIN)
    {
        $url = 'job/'.str_replace(' ', '%20', $this->masterJob.$jobName).'/build';
        $this->callJenkins($url, false, $role, $params);
    }

    /**
     * Build running job data
     *
     * Get running jobs and return them.
     */
    public function buildRunningJobData()
    {
        $runningJob = $this->getRunningJob();
        if ($runningJob !== null) {
            $number = $runningJob['lastBuild']['number'];
            $user = $this->getLauncher($runningJob['name'], $number);

            return array(
                'name' => str_replace($this->masterJob, '', str_replace($this->atomicJob, '', $runningJob['name'])),
                'number' => $number,
                'user' => $user
            );
        }

        return null;
    }

    /**
     * Get jobs list
     *
     * Get all jobs list.
     */
    public function getJobsList($role)
    {
        $url = 'view/TID/api/json?tree=jobs[name,color,lastBuild[number]]';
        $jobsList = $this->callJenkins($url, true, $role);

        if (empty($jobsList)) {
            return null;
        }

        $jobs = array();
        foreach ($jobsList['jobs'] as $val) {
            $job = array(
                'name' => str_replace($this->masterJob, '', $val['name']),
                'color' => $val['color'],
            );

            // MASTER JOB - Import FH must be ordered last
            $job['order'] = ($val['name'] == 'IV - MASTER JOB - Import FH') ? (count($jobsList)) : 0;

            if ($job['color'] == 'blue') {
                $job['color'] = 'green';
            }

            if (strpos($job['color'], '_anime') !== false) {
                $job['number'] = $val['lastBuild']['number'];
                $job['launcher'] = $this->getLauncher($val['name'], $job['number']);
                $job['color'] = 'blue';
            }

            array_push($jobs, $job);
        }

        usort($jobs, function ($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }

            return ($a['order'] < $b['order']) ? -1 : 1;
        });

        return $jobs;
    }

    public function buildRequestParam($jobName, $params)
    {
        switch ($jobName) {
            case 'Import FH':
                $parameters = [
                    'json' => json_encode([
                        'parameter' => [[
                            'name' => 'import_list',
                            'value' => implode(',', $params)
                        ]]
                    ])
                ];

                break;
            default:
                throw new \Exception('job inconnu');
        }

        return $parameters;
    }
}
