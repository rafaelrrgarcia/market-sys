<?php

class URLConfigs extends Routes
{
    private function checkUrlParameters($url, $routes) // Do the routes verifications above the URL
    { 
        try {
            $dataReturn = [ // Default return if not found
                'class' => '',
                'action' => '',
                'found' => false,
            ];

            // Check if URL have get params and clean it
            $urlClean = $url;
            if (strpos($url, '?') !== false) {
                $url = explode('?', $url);
                $urlClean = $url[0];
            }

            // Check if URL is in the routes
            foreach ($routes as $routeUrl => $routeClassFunction) {
                if ($urlClean == $routeUrl || $urlClean == '/'.$routeUrl) { // Check if full url
                    $dataReturn = $this->formatDataReturn($routeClassFunction);
                    break;
                } else { // Check parameters
                    $found = $this->checkVariablesInUrl($urlClean, $routeUrl);
                    if ($found != false) {
                        $dataReturn = $this->formatDataReturn($routeClassFunction, $found);
                        break;
                    }
                }
            }

            return $dataReturn;

        } catch (Exception $e) {
            return $e;
        }
    }

    private function formatDataReturn($routeClassFunction, $extraParams = false) // Get CLASS and FUNCTION from the URL route
    {
        $routeParts = explode("@", $routeClassFunction);
        $dataReturn = [
            'class' => $routeParts[0],
            'action' => $routeParts[1],
            'found' => true
        ];

        if ($extraParams) $dataReturn = array_merge($dataReturn, $extraParams);

        return $dataReturn;
    }

    private function checkVariablesInUrl($url, $routeURL) // Check special URL with variables
    {
        $extraParams = [];
        $urlChecker = [];

        //Remove the first / in the url if haves
        if (substr($url, 0, 1) == "/") $url = substr($url, 1);

        $explodedUrl = explode("/", $url);
        $explodedRoute = explode("/", $routeURL);

        // Search for variable positioning
        for ($i = 0; $i < count($explodedRoute); $i++) {
            if (strpos($explodedRoute[$i], ':') !== false && isset($explodedUrl[$i])) {
                $variable = str_replace(":", "", $explodedRoute[$i]);
                $extraParams[$variable] = $explodedUrl[$i];
                $urlChecker[] = (isset($explodedRoute[$i])) ? $explodedRoute[$i] : "";
            } else {
                $urlChecker[] = (isset($explodedUrl[$i])) ? $explodedUrl[$i] : "";
            }
        }

        if (count($extraParams) == 0 || implode("/", $urlChecker) != $routeURL) $extraParams = false;
        return $extraParams;
    }

    public function getClassFunction($url)
    {
        try {
            $routes = [];

            switch ($_SERVER['REQUEST_METHOD']) {
                case "GET":
                    $routes = $this->get($url);
                    break;
                case "POST":
                    $routes = $this->post($url);
                    break;
                case "DELETE":
                    $routes = $this->delete($url);
                    break;
            }

            $dataReturn = $this->checkUrlParameters($url, $routes);
            return $dataReturn;

        } catch (Exception $e) {
            return $e;
        }
    }
}
