<?php

class Model
{
    protected $db;
    protected $tableName;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    protected function checkSQLStateError($e, $addText = ""){
        if (strpos($e->getMessage(), 'SQLSTATE') !== false) { 
            return [
               'success' => false,
               'message' => trim('SQL intern error. ' . $addText)
           ];
       } else {
            return [
               'success' => false,
               'message' => $e->getMessage()
           ];
       }
    }
}
