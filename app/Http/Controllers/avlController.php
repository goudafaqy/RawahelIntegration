<?php

namespace App\Http\Controllers;

use  DB;
use App\Models\Trip;
use App\Models\LastStatus;
use App\Models\AmpObject;


class avlController extends Controller
{


    public function index()
    {

        $currentDateEnd = date('Y-m-d').' 23:59:59';
        $currentDateStart= date('Y-m-d').' 00:00:00';
        $trips  =     Trip::where('PLANNEDEND','<', $currentDateEnd)->
                            where('PLANNEDSTART','>', $currentDateStart)->
                            get(['RECID','SERIALNUM','PLANNEDSTART','PLANNEDEND','LOCATION','ROUTE','NAME']);
      
       
        
       return ['data'=>$trips];

      
    }

  
  
    
}
