<?php

namespace App\Http\Controllers;

use  DB;

class eventsController extends Controller
{
    public function index($type='')
    {

        if(isset($type) && $type!=''){
            $data['data'] =  array(
                "limit" => 1,
                'filters' => [
                    "type" => $type
                ]
                
            );

        }else{
            $data['data']=[];
        }
       
        $login=new authController();
        $auth=  $login->login ();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/events/lists?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function data($filters)
    {

        $data['data'] =  array(
            
                "filters" => $filters,
                "simplify"=> false,
                "pagination"=> false,
            
        );
        $login=new authController();
        $auth=  $login->login();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/events/data?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function AddUnplanned($trips)
    {

        $data['data'] =  $trips;
        $login=  new authController();
        $auth =  $login->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/rawahel/checkUnplanned?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function unplanned()
    {

        $data['data'] =  [];
        $login=  new authController();
        $auth =  $login->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/rawahel/lists?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function view($id)
    {

       
        $data['data'] =  array('id' => [$id]);
        $login=new authController();
        $auth=  $login->login ();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/events/view?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function getAddress($points)
    {

        $data['data'] =  ($points);
        $login=new authController();
        $auth=  $login->login();
        //return $data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') ."/units/getAddress?token=".config('app.avl_token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode ($response ,true);
    }

    public function getTripData($duration=[])
    {
        $results=[]; 
        $results['start']='';
        $results['end']='';
        $results['endKM']='';
        $results['startKM']='';
        $results['status']=0;
        $filters= ['from'   =>    $duration['from'] ,
                   'to'     =>    $duration['to'] ,
                   'unit_id'=>    $duration['unit_id']
        ] ;  
        if(isset($this->data($filters)['data'])) {

               
            $events      =      $this->data($filters)['data'];
            foreach($events  as $event ){
                $details=$this->view($event['event_id']);
                if( isset($details['data']) && $details!=null ){
                    $type= $details['data'][0]['type'];
                    if($type =='zone_out'){

                        $results['start']=date('Y-m-d H:i:s',$event['pos']['dtt'] / 1000);
                        $results['status']=1;
                        $results['startKM']=$event['pos']['trl'];

                    }if($type == 'zone_in'){

                        $results['end']=date('Y-m-d H:i:s',$event['pos']['dtt'] / 1000);
                        $results['endKM']=$event['pos']['trl'];
                        $results['status']=2;
                    }
            }

        }
    }
    return $results;
   }   
    
}
