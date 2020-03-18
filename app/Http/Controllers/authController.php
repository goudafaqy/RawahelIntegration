<?php

namespace App\Http\Controllers;

use  DB;

class authController extends Controller
{


    public function login()
    {



        $value = config('app.avl_token');

        if ($value == '' || $value == null) {

            $data['data'] =  array(
                'username' => (env('AVL_USERNAME')),
                'password' => (env('AVL_PASSWORD')),
            );


            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => env('AVL_URL') . "/auth/login",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
                )
            );

            $response = curl_exec($curl);
            curl_close($curl);
            $token = json_decode($response, true)['data']['token'];
            config(['app.avl_token' => $token]);
            return $token;
        } else {
            return $value;
        }
    }

    public function logout()
    {

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => env('AVL_URL') . "/auth/logout?token=" . config('app.avl_token'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
        return  $response;
    }
}
