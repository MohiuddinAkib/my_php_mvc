<?php

namespace App\controllers;

use App\libraries\Controller;

class HomeController extends Controller
{
  public function index()
  {
    view('index');
  }
}