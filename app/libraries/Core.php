<?php

namespace App\libraries;

class Core
{
  
  protected $currentController = 'HomeController';
  protected $currentMethod = 'index';
  protected $params = [];
  protected $router;
  
  /**
  * Gets url and call corresponding controller & method
  */
  public function __construct($router) {
    // Get request method
    $requestMethod = strtolower($_SERVER['REQUEST_METHOD']) . 's';
    // Init router
    $this->router = $router;
    $routes = $this->router->$requestMethod;
    $url = $this->getUrl();
    if($url !== NULL) {
      $url = $this->matchRoute($url, $routes);
    }
    
    if(file_exists('../app/controllers/'.$url[0].'.php')) {
      // If exists, set as controller
      $this->currentController = $url[0];
      // Unset 0 index -> $url now starts with index 1
      unset($url[0]);
    } else {
      $this->currentController = $this->currentController;
    }
    
    // Require the controller
    $definition = $this->currentController;
    $class = 'App\\controllers\\' . $definition;
    $foo = new $class();
    
    // Instantiate controller class
    // ex) $currentController = new PageController;
    $this->currentController = $foo;
    
    // Check for the second part of the url
    if(isset($url[1])) {
      // Check to see if the method exists in controller
      if(method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        // Unset 1 index
        unset($url[1]);
      }
    }
    
    // Get params
    $this->params = $url ? array_values($url) : [];
    // dd($this->currentMethod);
    // Call a callback with array of params
    call_user_func_array([$this->currentController, $this->currentMethod],$this->params);
  }
  
  
  /**
  * Sanitize $_GET['url'] and split urls into an array
  * 
  * $this->getUrl()
  * 
  * @return array $url
  */
  public function getUrl() {
    if(isset($_GET['url'])) {
      // removes the ending '/' if exists
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
  
  /**
  * Matches requested url with registerd routes
  * 
  * $url = $this->matchRoute($url, $routes);
  * 
  * @param array $url
  * @param array $routes
  * 
  * @return array $url
  */
  public function matchRoute($url, $routes) {
    foreach($routes as $route=>$controllerAction) {
      $tmp = ltrim($route, '/');
      $tmp = explode('/', $tmp);
      if(count($url) === count($tmp)) {
        for($i=0 ; $i < count($url) ; $i++) {
          if($tmp[$i] !== $url[$i] && strpos($tmp[$i], ':') !== false) {
            if(is_numeric($url[$i])) {
              $tmp[$i] = $url[$i];
            }
          }
        } 
      }
      
      if(count(array_diff($tmp, $url)) === 0) {
        if(is_callable($controllerAction[count($controllerAction)-1])) {
          $controllerAction[0]($url[count($url)-1]);
          exit();
        }
        if(is_numeric($url[count($url)-1])) {
          array_push($controllerAction, $url[count($url)-1]);
        }
        $url = $controllerAction;
        break;
      }
    }
    
    return $url;
  }
}

