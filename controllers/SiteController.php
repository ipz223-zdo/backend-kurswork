<?php

namespace controllers;

use core\Controller;

class SiteController extends Controller
{
    public function actionIndex(): array
    {
        return $this->render();
    }

    public function actionError(): array
    {
        return $this->render();
    }
}