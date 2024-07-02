<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    //
     function getdate ()
    {
        return["name"=>"John", "email"=>"john@example.com"];
    }
}
