<?php

class ProductController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can handle products
        $this->setPermissions(['auth']);
    }

    public function index($params)
    {
        // Model actions
        $products = new Product();
        $foundProducts = $products->index();
        if($foundProducts['success'])
            $this->printJson($foundProducts);
        else 
            $this->printErrorJson($foundProducts['message'], 400);
    }

    public function create($params)
    {

        // Check required fields
        $requiredFields = ['name', 'value', 'id_type'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || $params[$field] == '')
                $this->printEmptyFieldJson($field);
        }

        // Model actions
        $products = new Product();
        $foundProduct = $products->create($params);
        if($foundProduct['success'])
            $this->printJson($foundProduct, 201);
        else 
            $this->printErrorJson($foundProduct['message'], 400);
    }

    public function read($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $products = new Product();
        $foundProduct = $products->read($params);
        if($foundProduct['success'])
            $this->printJson($foundProduct);
        else 
            $this->printErrorJson($foundProduct['message'], 400);
    }

    public function modify($params)
    {
        // Validations
        $requiredFields = ['name', 'value', 'id_type'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || $params[$field] == '')
                $this->printEmptyFieldJson($field);
        }

        // Model actions
        $products = new Product();
        $foundProduct = $products->modify($params);
        if($foundProduct['success'])
            $this->printJson($foundProduct);
        else 
            $this->printErrorJson($foundProduct['message'], 400);
    }

    public function delete($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $products = new Product();
        $foundProduct = $products->delete($params);
        if($foundProduct['success'])
            $this->printJson($foundProduct);
        else 
            $this->printErrorJson($foundProduct['message'], 400);
    }
}
