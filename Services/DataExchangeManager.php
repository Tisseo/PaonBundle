<?php

namespace Tisseo\PaonBundle\Services;

class DataExchangeManager
{
    private $jenkinsServer;
    private $jenkinsUser;
    private $masterJob;
    private $atomicJob;

    public function __construct($jenkinsServer, $jenkinsUser, $masterJob, $atomicJob)
    {
        $this->jenkinsServer = $jenkinsServer;
        $this->jenkinsUser = $jenkinsUser;
        $this->masterJob = $masterJob;
        $this->atomicJob = $atomicJob;
    }

    /**
     * Request Jenkins
     * @param string $url
     * @param boolean $returnJson
     *
     * Call Jenkins by generating a curl request.
     * If $returnJson is true, return the response data as JSON.
     */
    private function callJenkins($url, $returnJson = false)
    {
        $request =  curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_USERPWD, $this->jenkinsUser);
        curl_setopt($request, CURLOPT_URL, $this->jenkinsServer.$url);

        $response = curl_exec($request);
        curl_close($request);

        $data = null;
        if ($returnJson)
            $data = json_decode($response, true);

        return $data;
    }

    /**
     * Get running job
     *
     * Check a job is running or not.
     */
    public function getRunningJob()
    {
        $url = "view/IV%20-%20FLUX%20DE%20DONNEE/api/json?tree=jobs[name,color,lastBuild[number]]";
        $jsonData = $this->callJenkins($url, true);

        // search for master job running
        foreach ($jsonData["jobs"] as $key => $val) {
            if (strpos($val["name"], $this->masterJob) !== false && strpos($val["color"], "_anime") !== false) {
                return $val;
            }
        }
        // if no master job found, search for any running job
        foreach ($jsonData["jobs"] as $key => $val) {
            if (strpos($val["color"], "_anime") !== false)
                return $val;
        }
        // no running job (in view "FLUX DE DONNEES")
        return null;
    }

    /**
     * Get launcher
     * @param string $jobName
     * @param string $number
     *
     * Get the launcher of a specific job.
     */
    public function getLauncher($jobName, $number)
    {
        $url = "job/".str_replace(" ", "%20", $jobName)."/".$number."/api/json?tree=actions[causes[userName,upstreamProject]],building";
        $jsonData = $this->callJenkins($url, true);

        if (array_key_exists("userName", $jsonData["actions"][0]["causes"][0]))
            return $jsonData["actions"][0]["causes"][0]["userName"];

        if (array_key_exists("upstreamProject", $jsonData["actions"][0]["causes"][0]))
            return str_replace($this->masterJob, "", str_replace($this->atomicJob, "", $jsonData["actions"][0]["causes"][0]["upstreamProject"]));

        return "";
    }

    /**
     * Launch job
     * @param string $jobName
     *
     * Launch a specific job.
     */
    public function launchJob($jobName)
    {
        $url = "job/".str_replace(" ", "%20", $this->masterJob.$jobName)."/build";
        $this->callJenkins($url, false);
    }

    /**
     * Build running job data
     *
     * Get running jobs and return them.
     */
    public function buildRunningJobData()
    {
        $runningJob = $this->getRunningJob();
        if ($runningJob !== null)
        {
            $number = $runningJob["lastBuild"]["number"];
            $user = $this->getLauncher($runningJob["name"], $number);

            return array(
                "name"=> str_replace($this->masterJob, "" ,  str_replace($this->atomicJob, "", $runningJob["name"])),
                "number" => $number,
                "user" => $user
            );
        }

        return null;
    }

    /**
     * Get jobs list
     *
     * Get all jobs list.
     */
    public function getJobsList()
    {
        $url = "view/TID/api/json?tree=jobs[name,color,lastBuild[number]]";
        $jobsList = $this->callJenkins($url, true);

        if (empty($jobsList))
            return null;

        $jobs = array();
        foreach ($jobsList["jobs"] as $val) {
            $job = array(
                "name"=> str_replace($this->masterJob, "",  $val["name"]),
                "color" => $val["color"],
            );

            if ($job["color"] == "blue")
                $job["color"] = "green";

            if (strpos($job["color"], "_anime") !== false) {
                $job["number"] = $val["lastBuild"]["number"];
                $job["launcher"] = $this->getLauncher($val["name"], $job["number"]);
                $job["color"] = "blue";
            }

            array_push($jobs, $job);
        }

        return $jobs;
    }
}
