<?php

namespace core;

class Router
{
    protected $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function run()
    {
        $parts = explode('/', $this->route);
        if (strlen($parts[0]) == 0) {
            $parts[0] = 'product';
            $parts[1] = 'index';
        }
        if (count($parts) == 1) {
            $parts[1] = 'index';
        }
        Core::get()->moduleName = $parts[0];
        Core::get()->actionName = $parts[1];
        $controller = 'controllers\\' . ucfirst($parts[0]) . 'Controller';
        $method = 'action' . ucfirst($parts[1]);
        if (class_exists($controller)) {
            $controllerObject = new $controller();
            Core::get()->controllerObject = $controllerObject;
            if (method_exists($controller, $method)) {
                array_splice($parts, 0, 2);
                return $controllerObject->$method($parts);
            } else
                return $this->error404();
        } else
            return $this->error404();
    }

    public function end()
    {

    }

    public function error404()
    {
        http_response_code(404);
        header("Location: /product/error404");
    }
}