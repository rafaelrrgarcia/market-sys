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
            foreach ($contents as $paramKey => $paramName) {
                $params[$paramKey] = addslashes($paramName);
            }
        }
        return $params;
    }
}
