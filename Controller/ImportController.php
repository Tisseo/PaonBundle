<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ImportController extends AbstractController
{
	const server = 'http://srv-dev03:7070/';
	const jenkins_user = "TID:codesecret1234#";
	const job_prefix =  "IV - MASTER JOB - ";

    public function editAction(Request $request, $jobName)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');

		$jobs_url = 'view/TID/api/json?tree=jobs[name,color,lastBuild[number]]';;
		$run_job_url = self::server."job/".str_replace(" ", "%20", self::job_prefix.$jobName)."/build";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $run_job_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_USERPWD, self::jenkins_user);
		$json=curl_exec($ch);
		curl_close($ch);

        //return ($this->importAction($request));
		 return $this->redirect($this->generateUrl('tisseo_datawarehouse_import'));
    }

    public function importAction(Request $request)
    {
        $this->isGranted('BUSINESS_MANAGE_IMPORTS_EXPORTS');
        $importManager = $this->get('tisseo_datawarehouse.import_manager');
		
		
		// get jobs list
		$jobs_url = 'view/TID/api/json?tree=jobs[name,color,lastBuild[number]]';;
		$json_jobs_url = self::server.$jobs_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$json_jobs_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, self::jenkins_user);
		$json=curl_exec($ch);
		$data = json_decode($json, true);
		$jobs = array();
		$running = false;
		foreach($data["jobs"] as $key=>$val){
			$job = array(
				"name"=> str_replace ( self::job_prefix, "" ,  $val["name"]),
				"color" => $val["color"],
				);				
			
			if($job["color"] == "blue_anime") {
				$job["number"] = $val["lastBuild"]["number"];
				
				//get launcher
				$launcher_url = self::server."job/".str_replace ( " " , "%20" ,  $val["name"])."/".$job["number"]."/api/json?tree=actions[causes[userName]],building";
				curl_setopt($ch, CURLOPT_URL, $launcher_url);
				$launcher_json=curl_exec($ch);
				$launcher_dict = json_decode($launcher_json, true);
				$launcher = "";
				if(array_key_exists ( "userName" , $launcher_dict["actions"][0]["causes"][0] )) {
					$job["launcher"] = $launcher_dict["actions"][0]["causes"][0]["userName"];
				}

				$job["color"] = "blue";
				$running = true; 
			}
			if($job["color"] == "blue"){ $job["color"] = "green"; }
			
			array_push($jobs, $job);
		}
		
		curl_close($ch);
        return $this->render(
            'TisseoDatawarehouseBundle:Import:imports.html.twig',
            array(
                'pageTitle' => 'menu.import_manage',
                'jobs' => $jobs,
				'running' => $running
            )
        );
    }
}
