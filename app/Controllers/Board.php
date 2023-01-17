<?php

namespace App\Controllers;
class Board extends BaseController
{
    public function index()
    {
        return view('board');
    }
}