<?php

namespace controllers;

use core\Controller;
use core\Core;
use core\Template;

class UserController extends Controller
{
    public function actionAdd()
    {
     return $this->render();
    }
    public function actionDelete()
    {
     return $this->render();
    }
    public function actionIndex()
    {
        $db = Core::get()->db;
        $rows = $db->select("laptops", ["name", "description", "brand"], [
            "id" => 2
        ]);
        return $this->render();
    }
}