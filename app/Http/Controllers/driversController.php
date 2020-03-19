<?php

namespace App\Http\Controllers;

class driversController extends Controller
{


    public function index()
    {

        $data['data'] =  array(
            "limit" => 5


        );
        $login = new authController();
        $auth =  $login->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') . "/drivers/lists?token=" . $auth,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function bind($input)
    {

        $data['data'] =  array(
            "driver_id" => $input['driver_id'],
            "unit_id" => $input['unit_id']

        );
        $login = new authController();
        $auth =  $login->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('AVL_URL') . "/drivers/set_unit_driver?token=" . $auth,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
