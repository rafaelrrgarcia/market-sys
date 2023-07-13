<?php

class Routes
{
    protected function get($url) // Define GET routes
    { 
        return $routes = [
            // Frontend token verification
            'me' => 'AuthController@me',
            // User routes
            'users' => 'UserController@index',
            'users/:id' => 'UserController@read',
            // Product routes
            'products' => 'ProductController@index',
            'products/:id' => 'ProductController@read',
            // Product Type routes
            'producttypes' => 'ProductTypeController@index',
            'producttypes/:id' => 'ProductTypeController@read',
            // Sales routes
            'sales' => 'SaleController@index',
            'sales/:id' => 'SaleController@read'
        ];
    }

    protected function post($url) // Define POST routes
    {
        return $routes = [
            // Login auth route
            'auth' => 'AuthController@login',
            // User routes
            'users' => 'UserController@create',
            'users/:id' => 'UserController@modify',
            // Product routes
            'products' => 'ProductController@create',
            'products/:id' => 'ProductController@modify',
            // Product Type routes
            'producttypes' => 'ProductTypeController@create',
            'producttypes/:id' => 'ProductTypeController@modify',
            // Sales routes
            'sales' => 'SaleController@create'
        ];
    }

    protected function delete($url) // Define DELETE routes
    {
        return $routes = [
            // User routes
            'users/:id' => 'UserController@delete',
            // Product routes
            'products/:id' => 'ProductController@delete',
            // Product Type routes
            'producttypes/:id' => 'ProductTypeController@delete',
            // Sales routes
            'sales/:id' => 'SaleController@delete'
        ];
    }
}
