<?php

namespace App\controllers;

use App\models\User;
use App\libraries\Controller;

class UserController extends Controller
{
  private $model;
  
  public function __construct() {
    $this->model = new User();
  }

  /**
   * Fetch all users.
   * 
   * Show index page for all users.
   *
   * @return users
   */
  public function index()
  {
    $users = $this->model->all();
    view('user/index', compact('users'));
  }

  public function create()
  {
    view('user/create');
  }

  public function show($id)
  {
    echo $id;
  }
}