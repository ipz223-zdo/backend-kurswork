<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Users;

class UsersController extends Controller
{
    public function actionLogin()
    {
        if ($this->isPost){
        $user = Users::FindByLoginAndPassword($this->post->login, $this->post->password);
            if(!empty($user)){
                Users::LoginUser($user);
                header('Location: /');
                return $this->redirect('/');
            }else
                $this->template->setParam('error_message', 'Неправильний логін та/або пароль');
        }
        return $this->render();
    }
    public function actionLogout()
    {
        Users::LogoutUser();
        return $this->redirect('/users/login');
    }
}