<?php

namespace App\Http\Controllers;

class Randomizer extends Controller
{
    public function __invoke($max)
    {
        return view('random', ['number' => rand() % $max]);
    }
}
