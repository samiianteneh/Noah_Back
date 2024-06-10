<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testApi extends Controller
{
    //
     function getdate ()
    {
        return["name"=>"John", "email"=>"john@example.com"];
    }
}
