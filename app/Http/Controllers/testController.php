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
       
        
        
        $currentDateTime=date('Y-m-d').' 10:00:00';
        $currentDateMinusDay=date('Y-m-d' ,strtotime("-1 days")).' 10:00:00';
        $currentDate=date('Y-m-d').' 23:59:59';
        $instance  =      new eventsController();
        $unplanned =[];
        $duration = [   'from'=>    $currentDateMinusDay,
                        'to'=>      $currentDate
                    ];
                            
        $events      =      $instance->data($duration)['data'];
        foreach( $events as  $event){

            $trip = Trip::where("SERIALNUM",$event['unit']['operation_code'])
                        ->where("PLANNEDSTART",'<',$currentDateTime)
                        ->where("PLANNEDEND",'>',$currentDateTime)
                        ->where("PLANNEDEND",'<',$currentDate)
                        ->orderBy('SERIALNUM', 'DESC')->first();
           
            $points=['lat'=>(string)$event['pos']['loc']['coordinates'][1] ,'lng'=>(string)$event['pos']['loc']['coordinates'][0]];            
            $address=  $instance->getAddress($points)['data']['address'];                  
            
            $unplanned[]=['name'=>$event['unit_name'] ,
                          'busid'=>$event['unit']['operation_code'] ,
                          'date'=>date('Y-m-d'),
                          'time'=>date('H:i:s'),
                          'location'=> $address,
                          'driver1'=>'driver_name1',
                          'driver2'=>'driver_name2'];
        }
       return  $unplanned;
       $res= $instance->AddUnplanned($unplanned);
       return  $res;
       
        
       
      
        
      
    }


    
}
