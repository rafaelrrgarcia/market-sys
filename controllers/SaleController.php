<?php

class SaleController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can handle sales
        $this->setPermissions(['auth']);
    }

    public function index($params)
    {
        // Model actions
        $sales = new Sale();
        $foundSales = $sales->index();
        if($foundSales['success'])
            $this->printJson($foundSales);
        else 
            $this->printErrorJson($foundSales['message'], 400);
    }

    public function create($params)
    {
        try {
            $return = [];
            $return['success'] = false;
            if(count($params['products']) <= 0) throw new Exception("No sales to register", 400);

            
            // Instantiate Models
            $typeModel = new ProductType();
            $productModel = new Product();

            $billings = [];
            // Get product from database with names, values and taxes
            foreach($params['products'] as $billing){
                // Get product from database with names, values and types
                $product = $productModel->read(['id' => $billing['id']]);
                if(!$product['success']) throw new Exception($product['message'], 400);
                $product = $product['data'][0];
                // Get taxes of the product type by id_type
                $type = $typeModel->read(['id' => $product['id_type']]);
                if(!$type['success']) throw new Exception($type['message'], 400);
                $type = $type['data'];
                // Calculate type
                $taxValue = $type['tax'] * $product['productvalue'];
                // Calculate total value
                $totalValue = $product['productvalue'] + $taxValue;
                
                // Create billing
                $billings[] = [
                    'product_name' => $product['productname'],
                    'total_price_taxes' => ($taxValue),
                    'total_price_products' => ($product['productvalue']),
                    'final_price' => ($totalValue)
                ];
            }

            // Save the sale
            $sales = new Sale();
            $foundSale = $sales->create($this->loggedUser['id'], $billings);
            if($foundSale['success']){
                $return['success'] = true;
                $this->printJson($foundSale, 201);
            }
            else {
                $this->printErrorJson($foundSale['message'], 400);
            }
        } catch (\Exception $e) {
            $this->printErrorJson($e->getMessage(), $e->getCode());
        }
    }

    public function read($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $sales = new Sale();
        $foundSale = $sales->read($params);
        if($foundSale['success'])
            $this->printJson($foundSale);
        else 
            $this->printErrorJson($foundSale['message'], 400);
    }

    public function delete($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $sales = new Sale();
        $foundSale = $sales->delete($params);
        if($foundSale['success'])
            $this->printJson($foundSale);
        else 
            $this->printErrorJson($foundSale['message'], 400);
    }
}
