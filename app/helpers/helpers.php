<?php

// Die and dump data
function dd($data) {
  die((var_dump($data)));
}

// Redirect to another page
function redirect($page) {
  header('Location: ' . DOMAIN_URL . '/' . $page);
}

/**
* Include corresponding view file from a controller
* Best to use this function at the end of a controller method
* 
* view('user/login', $response->data);
* 
* @param string $view
* @param array $data
*/
function view($view, $data = []) {
  // Check for the view file
  if(file_exists("../app/views/{$view}.view.php")) {
    require_once "../app/views/{$view}.view.php";
    exit();
  } else {
    // View does not exist
    die('View does not exist');
  }
}