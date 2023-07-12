<?php

class Core
{
    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $urls = new URLConfigs();
        $classFunction = $urls->getClassFunction($url);

        if (!empty($url) && $url != '/' && $classFunction['class'] != '') {
            $currentController = $classFunction['class'];
            $currentAction = $classFunction['action']; 
        } else {
            $currentController = 'HomeController';
            $currentAction = 'index';
        }

        $c = new $currentController();
        call_user_func_array(array($c, $currentAction), [array_merge($classFunction, $this->getParams())]);
    }

    public function getParams()
    {
        $params = array();
        foreach ($_REQUEST as $paramKey => $paramName) {
            $params[$paramKey] = addslashes($paramName);
        }
        $contents = file_get_contents('php://input');
        $contents = json_decode($contents, true);
        if (!empty($contents)) {
            $params = array_merge($params, $this->recursiveArrayCheck($contents));
        }
        return $params;
    }

    private function recursiveArrayCheck($params){
        foreach ($params as $key => $value) {
            if(is_array($value)){
                $params[$key] = $this->recursiveArrayCheck($value);
            }else{
                $params[$key] = addslashes($value);
            }
        }
        return $params;
    }
}
