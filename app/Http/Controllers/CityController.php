<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class CityController extends Controller
{
    
    public function get(\Illuminate\Http\Request $request)
    {

        $data = $request->all();

//        dd($data);

        $search_term = null;

        $country_id = null;

        if(array_key_exists('q', $data) == true){
            $search_term = '%' . $data['q'] . '%';
        }
        if(array_key_exists('country_id', $data) == true){
            $country_id = $data['country_id'];
        }


        if(!is_null($search_term)){
            $cityData = \App\Models\City::where('value', 'LIKE', $search_term);
            if(!is_null($country_id)){
                $cityData->where('country_id', $country_id);
            }

        }else{

            if(!is_null($country_id)){
                $cityData = \App\Models\City::where('country_id', $country_id);
            }else{
                $cityData = new \App\Models\City();
            }

        }
//        dd($cityData->get());

        return $cityData->paginate(1000);//->paginate(10);

        
//        $search_term = $request->input('q');
//
//        $country_id;
//
//        if($request->has('country_id')) {
//            $country_id = $request->input('country_id');
//        }
//
//        if($request->has('form')) {
//
//            $form = $request->input('form');
//
//            if(is_array($form) == true) {
//
//                foreach($form as $index => $value) {
//
//                    if($value['name'] == 'country_id') {
//                        $country_id = $value['value'];
//                    }
//
//                }
//
//            }
//
//        }
//
//        if ($search_term)
//        {
//
//            $query = \App\Models\City::where('value', 'LIKE', '%'.$search_term.'%');
//
//        }
//        else
//        {
//
//            $query = new \App\Models\City();
//
//        }
//
//        if($country_id) {
//            $query->where('country_id', $country_id);
//        }
//
//        return $query->paginate(10);

    }

}
