<?php

namespace App\Http\Controllers;

use  DB;
use App\Models\Trip;
use App\Models\LastStatus;
use App\Models\AmpObject;
use GuzzleHttp\Client;

class testController extends Controller
{
    public function test()
    {
       

        //$date =date('Y-m-d 00:00:00');
        $dateto = date("Y-m-d H:i:m", strtotime("-30 days"));
        $datefrom = date("Y-m-d H:i:m", strtotime("-45 days"));
      
      // dd( $dateto .' '.$datefrom );
        $drivers = DB::select( DB::raw("SELECT EMPLOYEE_NUMBER , GPS__bSerial__bNumber , LASTEDIT_USERTIMESTAMP  FROM RUH1SERVICE.MEM.dbo.FleetAssetV2 
         where GPS__bSerial__bNumber <> '' AND EMPLOYEE_NUMBER <> '' AND LASTEDIT_USERTIMESTAMP > '$datefrom'
         AND LASTEDIT_USERTIMESTAMP < '$dateto' "));

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
                      OHRMSE_ANCHOR.EMPLOYEE_NUMBER = '17989'
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
                
                                echo  $name . ' - Binded To - ' . $drivername . ' <br>  ';
                            }
                            $results[] = ['unit_id'=>$res->GPS__bSerial__bNumber ,'driver_id'=>$res->EMPLOYEE_NUMBER];
                        }
                       

                    }
                    
                    
                }
                return $results;
       
        
       
      
        
      
    }


    
}
