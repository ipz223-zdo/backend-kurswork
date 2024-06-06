<?php

namespace core;

class Core
{
    public string $defaultLayoutPath = 'views/layouts/index.php';
    public string $moduleName;
    public string $actionName;
    public Router $router;
    public Template $template;
    public Session $session;
    public DB $db;
    public Controller $controllerObject;
    private static Core $instance;
    private function __construct()
    {
        $this->template = new Template($this->defaultLayoutPath);
        $host = Config::get()->dbHost;
        $name = Config::get()->dbName;
        $login = Config::get()->dbLogin;
        $password = Config::get()->dbPassword;
        $this->db = new DB($host, $name, $login, $password);
        $this->session = new Session();
        session_start();
    }
    public function run($route): void
    {
        $this->router = new Router($route);
        $params = $this->router->run();
        if (!empty($params))
            $this->template->setParams($params);
    }
    public function end(): void
    {
        $this->template->display();
        $this->router->end();
    }
    public static function get(): Core
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }
}