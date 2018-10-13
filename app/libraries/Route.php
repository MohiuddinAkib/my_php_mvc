<?php

namespace App\libraries;

class Route
{
  protected $currentController = 'HomeController';
  protected $currentMethod = 'index';
  protected $params = [];
  protected $router;
  
  private $request;
  
  public function __construct()
  {
    $this->request = isset($_GET['url']) ? $_GET['url'] : '/';
    
	}
  

  /**
   * Get request to show requested page
   *
   * @param [string] $route
   * @param [string] / [callback] $controllerAction
   * @return void
   */
  public function get($route, $controllerAction) 
  {
    if(isset($this->request)) {
      // removes the ending '/' if exists
      $url = rtrim($this->request, '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $tmp = ltrim($route, '/');
      $tmp = explode('/', $tmp);
      $url = explode('/', $url);
      
      /**
      * Checks to see if requested url and
      * 
      * defined route has same amount of
      * 
      * word and then checks if has any wild card
      * 
      * if has wild card then replace that wild card with
      * 
      * requested url's param
      */
      if(count($url) === count($tmp)) {
        for($i=0 ; $i < count($url) ; $i++) {
          if($tmp[$i] !== $url[$i] && strpos($route, ':') !== false) {
            if(is_numeric($url[$i])) {
              $tmp[$i] = $url[$i];
            }
          }
        }
        
        $route = implode('/', $tmp);
      }
      
      
      // Check to see if requested url and route matched
      if(implode('/', $url) == trim($route, "/")) {
        // Check wheteher controller passed as string or callback
        if(is_callable($controllerAction)) {
          // Pass the function as it is
          $controllerAction($url[count($url) - 1]);
        } else {
          if(gettype($controllerAction) === 'string') {
            $controllerAction = explode('@', $controllerAction);
            $controller = $controllerAction[0];
            $method = $controllerAction[1];
            
            // Require the controller
            $class = 'App\\controllers\\' . $controller;
            $foo = new $class();
            
            // Instantiate controller class
            // ex) $currentController = new PageController;
            $this->currentController = $foo;
            
            // Check to see if the method exists in controller
            if(method_exists($this->currentController, $method)) {
              $this->currentMethod = $method;
            } else {
              die("ERROR: METHOD: {$method}  DOES NOT EXIST IN CONTROLLER: {$controller}");
            }
            
            // $this->matchRoute($url, );
            
            // Unset o and 1 index of requested url
            unset($url[0]);
            unset($url[1]);
            
            // Get params
            $this->params = $url ? array_values($url) : [];
            
            // Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
          }
        }
      }
    }
  }
}