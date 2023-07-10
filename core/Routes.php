<?php

class Routes
{
    protected function get($url) // Define GET routes
    { 
        return $routes = [
            'me' => 'HomeController@me',
            'users' => 'UserController@index',
            'users/:id' => 'UserController@read',
            'producttypes' => 'ProductTypeController@index',
            'producttypes/:id' => 'ProductTypeController@read',
        ];
    }

    protected function post($url) // Define POST routes
    {
        return $routes = [
            'auth' => 'AuthController@login',
            'users' => 'UserController@create',
            'users/:id' => 'UserController@update',
            'producttypes' => 'ProductTypeController@create',
            'producttypes/:id' => 'ProductTypeController@update',
        ];
    }

    protected function delete($url) // Define DELETE routes
    {
        return $routes = [
            'users/:id' => 'UserController@delete',
            'producttypes/:id' => 'ProductTypeController@delete',
        ];
    }
}
