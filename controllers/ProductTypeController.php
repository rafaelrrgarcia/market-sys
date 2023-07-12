<?php

class ProductTypeController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can handle productsTypes types
        $this->setPermissions(['auth']);
    }

    public function index($params)
    {
        // Model actions
        $productsTypes = new ProductType();
        $foundTypes = $productsTypes->index();
        if($foundTypes['success'])
            $this->printJson($foundTypes);
        else 
            $this->printErrorJson($foundTypes['message'], 400);
    }

    public function create($params)
    {

        // Check required fields
        $requiredFields = ['name', 'tax'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || $params[$field] == '')
                $this->printEmptyFieldJson($field);
        }

        // Model actions
        $productsTypes = new ProductType();
        $foundType = $productsTypes->create($params);
        if($foundType['success'])
            $this->printJson($foundType, 201);
        else 
            $this->printErrorJson($foundType['message'], 400);
    }

    public function read($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $productsTypes = new ProductType();
        $foundType = $productsTypes->read($params);
        if($foundType['success'])
            $this->printJson($foundType);
        else 
            $this->printErrorJson($foundType['message'], 400);
    }

    public function update($params)
    {
        // Validations
        $requiredFields = ['name', 'tax'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || $params[$field] == '')
                $this->printEmptyFieldJson($field);
        }

        // Model actions
        $productsTypes = new ProductType();
        $foundType = $productsTypes->update($params);
        if($foundType['success'])
            $this->printJson($foundType);
        else 
            $this->printErrorJson($foundType['message'], 400);
    }

    public function delete($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $productsTypes = new ProductType();
        $foundType = $productsTypes->delete($params);
        if($foundType['success'])
            $this->printJson($foundType);
        else 
            $this->printErrorJson($foundType['message'], 400);
    }
}
