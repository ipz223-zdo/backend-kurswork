<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Users;

class UsersController extends Controller
{
    public function actionLogin(): ?array
    {
        if(Users::IsUserLogged())
            return $this->redirect('/');
        if ($this->isPost){
        $user = Users::FindByLoginAndPassword($this->post->login, $this->post->password);
            if(!empty($user)){
                Users::LoginUser($user);
                header('Location: /');
                return $this->redirect('/');
            }else
                $this->addErrorMessage('Неправильний логін та/або пароль');
        }
        return $this->render();
    }

    public function actionRegister(): array
    {
        if ($this->isPost){
            $login = $this->post->login;
            $user = Users::FindByLogin($login);
            if(!empty($user)){
                $this->addErrorMessage('Користувач із таким логіном вже існує');
            }
            if (strlen($this->post->login) === 0){
                $this->addErrorMessage('Логін не вказано');
            }
            if (strlen($this->post->password) === 0){
                $this->addErrorMessage('Пароль не вказано');
            }
            if (strlen($this->post->password2) === 0){
                $this->addErrorMessage('Пароль(ще раз) не вказано');
            }
            if ($this->post->password != $this->post->password2){
                $this->addErrorMessage('Паролі не співпадають');
            }
            if (strlen($this->post->firstname) === 0){
                $this->addErrorMessage('Ім\'я не вказано');
            }
            if (strlen($this->post->lastname) === 0){
                $this->addErrorMessage('Прізвище не вказано');
            }
            if(!$this->isErrorMessagesExist()){
                Users::RegisterUser($this->post->login, $this->post->password, $this->post->firstname, $this->post->lastname);
                return $this->redirect('/users/registersuccess');
            }
        }
        return $this->render();
    }
    public function actionRegistersuccess()
    {
        return $this->render();
    }
    public function actionLogout(): null
    {
        Users::LogoutUser();
        return $this->redirect('/users/login');
    }
}