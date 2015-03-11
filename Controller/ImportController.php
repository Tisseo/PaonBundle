<?php

namespace Tisseo\TidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ImportController extends AbstractController
{
	const server = 'http://srv-dev03:7070/';
	const jenkins_user = "TID:codesecret1234#";
	const p_job =  "IV - MASTER JOB - ";
	const p_atomic = "IV - ATOMIC JOB - ";
	
	private function _getRunningJob()
	{
		$curl_r =  curl_init();
		curl_setopt($curl_r, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_r, CURLOPT_POST, 1);
		curl_setopt($curl_r, CURLOPT_USERPWD, self::jenkins_user);

		$flux_url = self::server."view/IV%20-%20FLUX%20DE%20DONNEE/api/json?tree=jobs[name,color,lastBuild[number]]";
		curl_setopt($curl_r, CURLOPT_URL,$flux_url);
		$flux_json = curl_exec($curl_r);
		$flux_data = json_decode($flux_json, true);
		curl_close($curl_r);
		//search for master job running
		foreach($flux_data["jobs"] as $key=>$val){
			if (strpos($val["name"], self::p_job) !== false) {
				if(strpos($val["color"], "_anime") !== false) {
					return $val;
				}
			}
		}
		//if no master job founded, search for any running job
		foreach($flux_data["jobs"] as $key=>$val){
			if(strpos($val["color"], "_anime") !== false) {
				return $val;
			}
		}
		//no running job (in view "FLUX DE DONNEES")
		return null;
	}

	private function _getLauncher($jobName, $number)
	{
		$curl =  curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_USERPWD, self::jenkins_user);
		$launcher_url = self::server."job/".str_replace ( " " , "%20" ,  $jobName)."/".$number."/api/json?tree=actions[causes[userName,upstreamProject]],building";
		curl_setopt($curl, CURLOPT_URL, $launcher_url);
		$launcher_json=curl_exec($curl);
		curl_close($curl);
		$launcher_dict = json_decode($launcher_json, true);
		if(array_key_exists ( "userName" , $launcher_dict["actions"][0]["causes"][0] )) {
			return $launcher_dict["actions"][0]["causes"][0]["userName"];
		}
		if(array_key_exists ( "upstreamProject" , $launcher_dict["actions"][0]["causes"][0] )) {
			return str_replace ( self::p_job, "" ,  str_replace ( self::p_atomic, "" ,  $launcher_dict["actions"][0]["causes"][0]["upstreamProject"]));
		}
		return "";
	}

    public function editAction(Request $request, $jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');

		//running jobs ?
		if($this->_getRunningJob() !==null) {
			return $this->redirect($this->generateUrl('tisseo_tid_import'));
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, self::jenkins_user);

		//run selected job
		$run_job_url = self::server."job/".str_replace(" ", "%20", self::p_job.$jobName)."/build";
		curl_setopt($ch, CURLOPT_URL, $run_job_url);
		$json=curl_exec($ch);
		curl_close($ch);

		 return $this->redirect($this->generateUrl('tisseo_tid_import'));
    }

    public function importAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');
        $importManager = $this->get('tisseo_tid.import_manager');

		// get jobs list
		$jobs_url = 'view/TID/api/json?tree=jobs[name,color,lastBuild[number]]';;
		$json_jobs_url = self::server.$jobs_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$json_jobs_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, self::jenkins_user);
		$json = curl_exec($ch);
		$data = json_decode($json, true);

		//running jobs ?		
		$running = false;
		$running_job = "";

		$job = $this->_getRunningJob();
		if ($job !== null) {
			$running = true;
			$number = $job["lastBuild"]["number"];
			$user = $this->_getLauncher($job["name"], $number);

			$running_job = array(
				"name"=> str_replace ( self::p_job, "" ,  str_replace ( self::p_atomic, "" ,  $job["name"])),
				"number" => $number,
				"user" => $user
			);
		}

		//create job list
		$jobs = array();
		foreach($data["jobs"] as $key=>$val){
			$job = array(
				"name"=> str_replace ( self::p_job, "" ,  $val["name"]),
				"color" => $val["color"],
				);
			
			if(strpos($job["color"], "_anime") !== false) {
				$job["number"] = $val["lastBuild"]["number"];
				
				//get launcher
				$job["launcher"] = $this->_getLauncher($val["name"], $job["number"]);
				$job["color"] = "blue";
				$running = true; 
			}
			if($job["color"] == "blue"){ $job["color"] = "green"; }
			
			array_push($jobs, $job);
		}
		
		curl_close($ch);
        return $this->render(
            'TisseoTidBundle:Import:imports.html.twig',
            array(
                'pageTitle' => 'menu.import_manage',
                'jobs' => $jobs,
				'running_job' => $running_job,
				'running' => $running
            )
        );
    }
}
