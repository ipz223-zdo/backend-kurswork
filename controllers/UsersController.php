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
                var_dump($user);
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
    public function actionUpdate($id): array
    {
        $userID = (int) Core::get()->session->get('user')['id'];
        $userIDFromParameter = (int) $id[0];

        if ($userID !== $userIDFromParameter) {
            $this->redirect('/product/error403');
        }

        if ($this->isPost) {
            $login = $this->post->login;
            $password = $this->post->password;
            $password2 = $this->post->password2;
            $firstname = $this->post->firstname;
            $lastname = $this->post->lastname;

            $currentUser = Users::findById($userID);
            if (empty($login)) {
                $this->addErrorMessage('Логін не вказано');
            } elseif ($login !== $currentUser['login'] && Users::FindByLogin($login)) {
                $this->addErrorMessage('Користувач із таким логіном вже існує');
            }

            if (empty($password)) {
                $this->addErrorMessage('Пароль не вказано');
            }
            if (empty($password2)) {
                $this->addErrorMessage('Пароль (ще раз) не вказано');
            }
            if ($password !== $password2) {
                $this->addErrorMessage('Паролі не співпадають');
            }

            if (empty($firstname)) {
                $this->addErrorMessage('Ім\'я не вказано');
            }
            if (empty($lastname)) {
                $this->addErrorMessage('Прізвище не вказано');
            }

            if (!$this->isErrorMessagesExist()) {
                Users::UpdateUser($userID, $login, $password, $firstname, $lastname);
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