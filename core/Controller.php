<?php

namespace core;


use JetBrains\PhpStorm\NoReturn;

class Controller
{
    protected Template $template;
    public bool $isPost = false;
    public bool $isGet = false;
    public array $errorMessages;
    public Post $post;
    public Get $get;
    public function __construct()
    {
        $action = Core::get()->actionName;
        $module = Core::get()->moduleName;
        $path = "views/{$module}/{$action}.php";
        $this->template = new Template($path);
        switch ($_SERVER['REQUEST_METHOD']){
            case 'POST':
                $this->isPost=true;
                break;
            case 'GET':
                $this->isGet=true;
                break;
        }
        $this->post = new Post();
        $this->get = new Get();
        $this->errorMessages = [];
    }

    public function render($pathToView = null, $data = []): array
    {
        if (!empty($pathToView)) {
            $this->template->setTemplateFilePath($pathToView);
        }
        $this->template->setParams($data);
        return [
            'Content' => $this->template->getHTML()
        ];
    }

    #[NoReturn] public function redirect($path): void
    {
        header("Location: {$path}");
        die();
    }
    public function addErrorMessage($message = null): void
    {
        $this->errorMessages [] = $message;
        $this->template->setParam('error_message', implode( '<br>', $this->errorMessages));
    }
    public function clearErrorMessage(): void
    {
        $this->errorMessages = [];
        $this->template->setParam('error_message', null);
    }
    public function isErrorMessagesExist(): bool
    {
        return count($this->errorMessages) > 0;
    }
}