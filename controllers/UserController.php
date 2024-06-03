<?php

namespace controllers;

class UserController
{
 public function actionAdd()
 {
    return [
        'Title' => 'Реєстрація',
        'Content' => 'Новий користувач'
    ];
 }
 public function actionDelete()
 {
     return [
         'Title' => 'Видалення',
         'Content' => 'Видалення користувача'
     ];
 }
}