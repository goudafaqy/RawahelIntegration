<?php

namespace App\Http\Controllers;

use  DB;

class jomaihController extends Controller
{

    

    public function index()
    {

       
        
         $datefrom = date("Y-m-d", strtotime("-2 days"));
        $drivers = DB::select( DB::raw("SELECT EMPLOYEE_NUMBER , GPS__bSerial__bNumber , LASTEDIT_USERTIMESTAMP  FROM RUH1SERVICE.MEM.dbo.FleetAssetV2 
                                         where GPS__bSerial__bNumber <> '' AND EMPLOYEE_NUMBER <> '' AND LASTEDIT_USERTIMESTAMP > '$datefrom'  "));
          

                $avl_units  =      new unitsController();
                $avl_drivers  =      new driversController();
                $results = [];
                foreach($drivers as $res){
 
                     if(isset($res->GPS__bSerial__bNumber)){

                         $unit      =        $avl_units->index($res->GPS__bSerial__bNumber)['data'];
                         $driver      =      $avl_drivers->index($res->EMPLOYEE_NUMBER)['data'];

                         if($unit && $driver ){
 
                             $unit_id = $unit[0]['_id'];
                             $name = $unit[0]['name'];
                             $driver_id =$driver [0]['_id'];
                             $drivername =$driver [0]['name'];
                             $input = ['unit_id' => $unit_id, 'driver_id' => $driver_id];
                             $result = $avl_drivers->bind($input);

                             if ($result['status_code'] == 200) {
                 
                                 echo  $name . " - Binded To - " . $drivername . ' </br> ';
                             }
                             $results[] = ['unit_id'=>$res->GPS__bSerial__bNumber ,'driver_id'=>$res->EMPLOYEE_NUMBER];
                         }
                        
 
                     }
                     
                     
                 }
                 return $results;
        
         
        

    }
    public function group()
    {

         $datefrom = date("Y-m-d H:i:m", strtotime("-10 days"));
         $drivers = DB::select( DB::raw("SELECT EMPLOYEE_NUMBER   FROM RUH1SERVICE.MEM.dbo.FleetAssetV2 
                                         where GPS__bSerial__bNumber <> '' AND EMPLOYEE_NUMBER <> '' AND LASTEDIT_USERTIMESTAMP > '$datefrom'   "));
                
        $results = [];
        foreach($drivers as $res){

            $results[] = ['employeeId'=>$res->EMPLOYEE_NUMBER ,'groups'=>$this->getGroup($res->EMPLOYEE_NUMBER)];
                
        }
        return $results;

         
        

    }

    function getGroup($id){


        $groups = DB::select( DB::raw("WITH MyCTE AS
        (
               SELECT
                      OHRMSE_ANCHOR.EMPLOYEE_NUMBER,
                      OHRMSE_ANCHOR.FULL_NAME,
                      OHRMSE_ANCHOR.POSITION_NAME,
                      OHRMSE_ANCHOR.SUPERVISOR_EMPLOYEE_NUMBER,
                      OHRMSE_ANCHOR.SUPERVISOR_NAME,
                      OHRMSE_ANCHOR.PERSON_TYPE_ID
               FROM
                    RUH1SFADB.ABP_SFA_BMB.dbo.ORACLE_HRMS_EMPLOYEES OHRMSE_ANCHOR
               WHERE
                      OHRMSE_ANCHOR.EMPLOYEE_NUMBER = ".$id."
               UNION ALL
               SELECT
                      OHRMSE_RECURSIVE.EMPLOYEE_NUMBER,
                      OHRMSE_RECURSIVE.FULL_NAME,
                      OHRMSE_RECURSIVE.POSITION_NAME,
                      OHRMSE_RECURSIVE.SUPERVISOR_EMPLOYEE_NUMBER,
                      OHRMSE_RECURSIVE.SUPERVISOR_NAME,
                      OHRMSE_RECURSIVE.PERSON_TYPE_ID
               FROM
                      RUH1SFADB.ABP_SFA_BMB.dbo.ORACLE_HRMS_EMPLOYEES OHRMSE_RECURSIVE
                      INNER JOIN MyCTE ON OHRMSE_RECURSIVE.SUPERVISOR_EMPLOYEE_NUMBER = MyCTE.EMPLOYEE_NUMBER
               WHERE
                      OHRMSE_RECURSIVE.SUPERVISOR_EMPLOYEE_NUMBER IS NOT NULL
            )
                SELECT
                    *
                FROM
                    MyCTE
                WHERE
                    PERSON_TYPE_ID IN(6,102)"));

               return $groups;
    }
}
