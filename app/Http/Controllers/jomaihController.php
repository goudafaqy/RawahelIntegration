<?php

namespace App\Http\Controllers;

//use  DB;
//use App\Models\Trip;

class jomaihController extends Controller
{

    protected $imeis = ['862846042673056'];
    protected $drivers = ['5ddd109003ed52f927459646'];

    public function index()
    {

        $avl_units  =      new unitsController();
        $avl_drivers  =      new driversController();
        $results = [];
        for ($i = 0; $i < count($this->imeis); $i++) {

            $unit      =      $avl_units->index($this->imeis[$i])['data'][0];
            $unit_id = $unit['_id'];
            $name = $unit['name'];
            $driver_id      =     '5ddd109003ed52f927459646';
            $input = ['unit_id' => $unit_id, 'driver_id' => $driver_id];
            $result = $avl_drivers->bind($input);
            if ($result['status_code'] == 200) {

                echo " - " . $name . " Binded To " . $driver_id . "  -  ";
            }
        }

        //return $results;
    }
}
