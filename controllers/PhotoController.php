<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\ProductImages;

class PhotoController extends Controller
{
    public function actionDelete($id): array
    {
        if (Core::isUserAdmin()) {
            ProductImages::deletePhotoById($id[0]);
            return $this->render();
        } else {
            return $this->redirect('/');
        }
    }
}