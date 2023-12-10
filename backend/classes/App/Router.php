<?php

namespace App;

class Router
{
    private $routes = [];

    public function get($uri, $controllerMethod)
    {
        $this->routes['GET'][$uri] = $controllerMethod;
    }

    public function post($uri, $controllerMethod)
    {
        $this->routes['POST'][$uri] = $controllerMethod;
    }

    public function dispatch($method, $uri)
    {
        if (!$this->isMethodAllowed($method)) {
            echo "Method not allowed";
            return;
        }

        $this->handleRoute($method, $uri);
    }

    private function isMethodAllowed($method)
    {
        return isset($this->routes[$method]);
    }
    
    private function handleRoute($method, $uri)
    {
        foreach ($this->routes[$method] as $route => $handler) {
            $regexRoute = $this->getRegexFromRoute($route);

            if ($this->isMatchingRoute($regexRoute, $uri)) {
                if ($this->handleClosureRoute($handler)) {
                    return;
                }

                $params = $this->extractParamsFromUri($uri, $regexRoute);
                [$controller, $method] = explode('@', $handler);

                $controllerName = 'App\\Controllers\\' . $controller;
                $controllerInstance = new $controllerName();

                $this->invokeControllerMethod($controllerInstance, $method, $params);
                return;
            }
        }

        echo "Route not found";
    }

    private function handleClosureRoute($handler)
    {
        if ($handler instanceof Closure) {
            $handler(); // Execute the closure
            return true;
        }
        return false;
    }
    private function getRegexFromRoute($route)
    {
        $regexRoute = preg_replace('/\/{\w+}/', '/\w+', $route);
        return str_replace('/', '\/', $regexRoute);
    }

    private function isMatchingRoute($regexRoute, $uri)
    {
        return preg_match('/^' . $regexRoute . '$/', $uri);
    }

    private function extractParamsFromUri($uri, $regexRoute)
    {
        $matches = [];
        preg_match('/^' . $regexRoute . '$/', $uri, $matches);
        $params = explode('/', $matches[0]);
        array_shift($params);
        return $params;
    }

    private function invokeControllerMethod($controllerInstance, $method, $params)
    {
        call_user_func_array([$controllerInstance, $method], $params);
    }
}
