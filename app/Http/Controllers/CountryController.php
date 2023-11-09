<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class CountryController extends Controller
{
    
    public function get(\Illuminate\Http\Request $request)
    {
        
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = \App\Models\Country::where('value', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = \App\Models\Country::paginate(10);
        }

        return $results;

    }

}
