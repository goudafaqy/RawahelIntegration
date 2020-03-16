<?php

namespace App\Http\Controllers;

use  DB;

class unitsController extends Controller
{
    public function index()
    {

        
       
    $data['data'] =  array(
       "limit" => 2000,
        'projection' => [
            "last_update",    
            "basic",
            "sensors_last_val",
            "counters",
            "sensors"

                
        ]
        
    );

        
    $login=new authController();
    $auth=  $login->login ();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => env('AVL_URL') ."/units/lists?token=".config('app.avl_token'),
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
    //return count(json_decode ($response ,true)['data']);
    return json_decode ($response ,true);
    }
    
}
