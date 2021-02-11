<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRegistration extends Controller
{
    public function __invoke(Request $request)
    {
        $name = $request->input('name');
        echo 'Welcome, '.$name;
    }
}
