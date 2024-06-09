<?php

namespace controllers;

use models\Cart;
use models\Product;
use core\Controller;
use models\Users;

class CartController extends Controller
{
    public function actionIndex()
    {
        $userData = $this->getUserDataFromSession();
        $cart = new Cart();
        $items = $cart->getItems();
        $total = $cart->getTotal();

        return $this->render(null, [
            'items' => $items,
            'total' => $total,
            'userData' => $userData
        ]);
    }
    private function getUserDataFromSession()
    {
        if (Users::IsUserLogged()) {
            return [
                'email' => $_SESSION['user']['login'] ?? ''
            ];
        }
        return [];
    }

    public function actionAdd($productId)
    {
        $quantity = $_POST['quantity'] ?? 1;
        $cart = new Cart();
        $product = Product::findById($productId[0]);

        try {
            if ($product && $product['quantity'] > 0) {
                $cart->addProduct($productId[0], intval($quantity));
            }
            $this->redirect('/product/view/' . $productId[0]);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/cart/index/' . $productId[0]);
        }
    }

    public function actionRemove($productId)
    {
        $cart = new Cart();
        $cart->removeProduct($productId[0]);
        $this->redirect('/cart/index');
    }

    public function actionIncrease($productId)
    {
        $cart = new Cart();
        $product = Product::findById($productId[0]);
        if ($product && $product['quantity'] > $cart->getQuantity($productId[0])) {
            $cart->increaseQuantity($productId[0]);
        }
        $this->redirect('/cart/index');
    }

    public function actionDecrease($productId)
    {
        $cart = new Cart();
        $cart->decreaseQuantity($productId[0]);
        $this->redirect('/cart/index');
    }

    public function actionClear()
    {
        $cart = new Cart();
        $cart->clear();
        $this->redirect('/cart/index');
    }
}