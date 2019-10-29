<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NotificationController extends Controller {
 private $apiUrl;

    public function __construct()
    {
        //initialize class variables
        $this->apiUrl = env('API_URL');
    }
    public function checkStudentModuleStatus() {
        // get live modules
        $res = $this->getLiveModules();
        // loop through each module
        for ($i = 0; $i < count($res->results); $i++) {
            // check if today is mid date
            $check = $this->checkIfMidDateReached($res->results[$i]->start_date, $res->results[$i]->end_date);
            // if today is today is middate, else proceed to next module
            if ($check) {
                $module_id = $res->results[$i]->id;
                // loop through each student 
                for ($j = 0; $j < count($res->results[$i]->students); $j++) {
                    $student_id = $res->results[$i]->students[$j];
                    //get student module progression
                    $progression = $this->getProgression($module_id, $student_id);
                   // set correct status for the update
                    $status = ($progression->progress < 45) ? 'behind' : 'on track';
                    // set the notifiction payload
                    $payload = [
                        'title' => 'Progress update for the ' . $res->results[$i]->name . ' module',
                        'body' => 'You are currently <b> ' . $status . ' </b> in your progress for the ' . $res->results[$i]->name . ' module.'
                    ];
                    // notify the student of the current status for the module
                    $this->notifyStudentsMidModule($student_id, $payload);
                }
            }
        }
    }

    public function getLiveModules() {
        // make http call to get live modules
        $client = new Client();
        $res = $client->get($this->apiUrl.'/modules/live_modules');
        $modules = json_decode($res->getBody());
       // return modules object
        return $modules;
    }

    public function getProgression($module_id, $student_id) {
        // make http call to get live modules
        $client = new Client();
        $res = $client->get($this->apiUrl.'/progression/'.$module_id.'/'.$student_id);
        $progression = json_decode($res->getBody());
        // return progression object
        return $progression;
    }

    public function notifyStudentsMidModule($student_id,$payload) {
        // make http call to notify students mid-module
        $client = new Client();
        $client->put($this->apiUrl.'/notifications/'.$student_id, [
            'headers' => ['content-type' => 'application/json'],
        ], json_encode($payload));
        
    }
    public function checkIfMidDateReached($start_date, $end_date) {
        //get the mid date of the module start date and end date
        $midPoint = (strtotime($start_date) + strtotime($end_date)) / 2;
        //if todat is middate, return true, else false
        if ($midPoint === strtotime(date('d/m/Y'))) {
            return true;
        } else {
            return false;
        }
    }
}
