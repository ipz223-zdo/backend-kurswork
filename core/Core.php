<?php

namespace core;

class Core
{
    public $defaultLayoutPath = 'views/layouts/index.php';
    public $moduleName;
    public $actionName;
    public $router;
    public $template;
    public $db;
    private static $instance;
    private function __construct()
    {
        $this->template = new Template($this->defaultLayoutPath);
        $host = Config::get()->dbHost;
        $name = Config::get()->dbName;
        $login = Config::get()->dbLogin;
        $password = Config::get()->dbPassword;
        $this->db = new DB($host, $name, $login, $password);
    }
    public function run($route)
    {
        $this->router = new \core\Router($route);
        $params = $this->router->run();
        $this->template->setParams($params);
    }
    public function end()
    {
        $this->template->display();
        $this->router->end();
    }
    public static function get()
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }
}