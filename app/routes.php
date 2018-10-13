<?php

/**
* Define routes here with url
* 
* and desired controller or callback
*/

$route->get('/', 'HomeController@index');

$route->get('/users/create', 'UserController@create');

$route->get('/users/store', 'UserController@store');

$route->get('/users/show/:id', 'UserController@show');

$route->get('/users', 'UserController@index');
