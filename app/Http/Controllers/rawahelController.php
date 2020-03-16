<?php

namespace App\Http\Controllers;

use  DB;
use App\Models\Trip;
use App\Models\Emp;

use App\Models\LastStatus;
use App\Models\AmpObject;
use GuzzleHttp\Client;


class rawahelController extends Controller
{

    protected $busess=['1037','1189','1066','1139'];
    public function index()
    {

        $avl_units  =      new unitsController();
        $units      =      $avl_units->index()['data'];
        $currentDateTime=date('Y-m-d H:i:s');
        $currentDate=date('Y-m-d').' 23:59:59';
        $instance  =      new eventsController();
       
        foreach($units as $unit){
            
            $BusId=$unit['operation_code'];
            
            // Update Sales Trans Bus
            $trip = Trip::where("SERIALNUM",       $BusId)
                        ->where("PLANNEDSTART",'<',$currentDateTime)
                        ->where("PLANNEDEND",'>',  $currentDateTime)
                        ->where("PLANNEDEND",'<',  $currentDate)
                        ->orderBy('SERIALNUM', 'DESC')->first();

            if(isset($trip)){

                $duration = ['from'=>    $trip->PLANNEDSTART,
                            'to'=>      $trip->PLANNEDEND,
                            'unit_id'=> $unit['_id'] ];
            

                $event      =      $instance->getTripData($duration);
                $trip->OPR_STATUS = $event['status'];
                $trip->ACTUALSTART= $event['start'];
                $trip->ACTUALEND=   $event['end'];
                $trip->ACTUALKM=   ($event['endKM']!="" ? $event['endKM'] - $event['startKM'] : 0);
                if($trip->save()){

                     echo "<p> <strong>   ".$BusId."   </strong>   TransBusSales Updated  </p>"; echo "<br>";
                }

            }
            
            
            // Update Last Status
            $status = LastStatus::where("BUSID",$BusId)->orderBy('BUSID', 'DESC')->first();
            if(isset($status)){

                if(isset($unit['last_update']) ){
                    $points=['lat'=>(string)$unit['last_update']['lat'] ,'lng'=>(string)$unit['last_update']['lng']];
                     if(isset($instance->getAddress($points)['data'])){

                       
                        $address=  $instance->getAddress($points)['data']['address'];
                        $status->STREET= isset($address) ? $address : $status->STREET;
                     }          
                    
                }
                

                $status->LASTSTATUSTIME=isset($unit['last_update']['dtt'])? date('Y-m-d H:i:s', $unit['last_update']['dtt'] /1000): '';
                $status->X = isset($unit['last_update']['lat'])?$unit['last_update']['lat'] : '';
                $status->Y= isset($unit['last_update']['lng'])?$unit['last_update']['lng'] : '';
               
                
                if($status->save()){

                    echo "  <p> <strong> ".$BusId."     </strong>   Status updated  </p> "; echo "<br>";
                }

            }    

            // Update APMOBJECTTABLE
            $amp = AmpObject::where("APMOBJECTID",$BusId)->orderBy('APMOBJECTID', 'DESC')->first();
            if(isset($amp)){

                $amp->GROSSWIDTH = $this->getSensorVal('fuel',$unit['sensors']);
                $amp->APMPLANSEQMETERVALUE = strval($unit['counters']['odometer']);
                if($amp->save()){

                      echo " <p>  <strong>  ".$BusId."   </strong>   AMP Updated   </p>"; echo "<br>";
                }

            }    
        }

        //Check unplanned trips  

        
        $currentDateTime=date('Y-m-d').' 10:00:00';
        $currentDateMinusDay=date('Y-m-d' ,strtotime("-1 days")).' 10:00:00';
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
            if(!$trip){

                $points=['lat'=>(string)$event['pos']['loc']['coordinates'][1] ,'lng'=>(string)$event['pos']['loc']['coordinates'][0]];            
                $address=  $instance->getAddress($points)['data']['address'];                  
                $unplanned[]=[  'name'=>$event['unit_name'] ,
                                'busid'=>$event['unit']['operation_code'] ,
                                'date'=>date('Y-m-d'),
                                'time'=>date('H:i:s'),
                                'location'=> $address,
                                'driver1'=>'driver_name1',
                                'driver2'=>'driver_name2'];

            }
            
        }
        if(!empty($unplanned)){

           $res=$instance->AddUnplanned($unplanned);

           return  $res;

        } 
      
      

      
    }

  
    public function getSensorVal($type , $sensors)
    {

       
        foreach($sensors as $sensor){

            if(isset($sensor['type']) && isset($sensors)) {
                if($sensor['type'] == $type){
                    return isset($sensor['last_val']['value'])?$sensor['last_val']['value'] : '';
                }else{
                    return '0';
                }

            }else{
                return '0';
            }
        }
    
    }

    public function amps()
    {

        $results = AmpObject::get(['APMOBJECTID','APMPLANSEQMETERVALUE','GROSSWIDTH']);
        return json_encode( $results);


    }
    public function statues()
    {

        $results = LastStatus::whereIn("BUSID",$this->busess)->
        get();
        return json_encode( $results);
    }

    public function trips()
    {

        $currentDateStart = date('Y-m-d').' 00:00:00';
        $currentDateEnd   = date('Y-m-d').' 23:59:59';
        $results = Trip:: 
                          where('PLANNEDEND','<', $currentDateEnd)->
                          where('PLANNEDSTART','>', $currentDateStart)->
                          get(['PLANNEDSTART','PLANNEDEND' ,'SERIALNUM']);
        
        return json_encode( $results);


    }

    public function getEmployee($empID)
    {
        $result = Emp::find($empID)->first(['ARABICNAME']);
        return  $result->ARABICNAME;

    }


    
}
