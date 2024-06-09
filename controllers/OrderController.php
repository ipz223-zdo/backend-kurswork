<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Cart;
use models\Order;
use models\Users;

class OrderController extends Controller
{
    public function actionIndex()
    {
        if (Core::isUserAdmin()) {
            $orders = Order::findAll();
            return $this->render(null, ['orders' => $orders]);
        } else{
            $this->redirect('/');
        }
    }

    public function actionView($id)
    {
        $order = Order::findOne($id[0]);
        return $this->render(null, ['order' => $order]);
    }

    public function actionUser($userId)
    {
        $userID = (int) Core::get()->session->get('user')['id'];
        $userIDFromParameter = (int) $userId[0];
        if (!Core::isUserAdmin() && $userID !== $userIDFromParameter) {
            $this->redirect('/product/error403');
        }

        $orders = Order::findByUserId($userId[0]);

        return $this->render(null, ['orders' => $orders]);
    }

    public function actionFind()
    {
        if (!empty($_POST['order_id'])) {
            $orderId = $_POST['order_id'];

            $order = Order::findOne($orderId);
            if ($order) {
                $this->redirect("/order/view/" . $orderId);
                exit;
            } else {
                $this->addErrorMessage('Замовлення з таким ідентифікатором не знайдено.');
                return $this->render();
            }
        }
        return $this->render();
    }

    private function getTotalAmountFromCart()
    {
        $cart = new Cart();
        return $cart->getTotal();
    }

    private function getTotalProductsFromCart()
    {
        $cart = new Cart();
        return $cart->getItems();
    }

    public function actionAdd()
    {
        $totalAmount = $this->getTotalAmountFromCart();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['total_amount'] = $totalAmount;
            $data['user_id'] = $_SESSION['user']['id'] ?? null;
            $this->validateOrderData($data);

            $products = $this->getTotalProductsFromCart();

            if (!$this->isErrorMessagesExist()) {
                $order = new Order();
                foreach ($data as $key => $value) {
                    $order->$key = $value;
                }
                $order->status = 'pending';
                $order->products_json = json_encode($products);
                $order->save();
                $this->redirect('/cart/clear/');
            }
        } else {
            return $this->render(null, ['totalAmount' => $totalAmount]);
        }
        return $this->render();
    }

    public function actionUpdatestatus($data)
    {
        $order = Order::findOne($data[0]);
        if ($order !== null) {
            $allowedStatuses = ['pending', 'accepted', 'shipped', 'delivered', 'canceled'];
            if (in_array($data[1], $allowedStatuses)) {
                $orderData = ['status' => $data[1]];
                Core::get()->db->update('orders', $orderData, ['order_id' => $data[0]]);
                return $this->redirect('/order/view/' . $data[0]);
            } else {
                $this->addErrorMessage('Неприпустимий статус.');
            }
        } else {
            $this->addErrorMessage('Замовлення не знайдено.');
        }
        return $this->render();
    }

    public function actionCancel($orderId)
    {
        $order = Order::findByID($orderId[0]);
        if ($order !== null) {
            if ($order['status'] === 'pending' || $order['status'] === 'accepted') {
                $orderData = [
                    'status' => 'canceled'
                ];
                Core::get()->db->update('orders', $orderData, ['order_id' => $orderId[0]]);
                return $this->redirect('/order/view/' . $orderId[0]);
            } else {
                $this->addErrorMessage('Неможливо скасувати це замовлення.');
            }
        } else {
            $this->addErrorMessage('Замовлення з таким ідентифікатором не знайдено.');
        }
        return $this->render();
    }

    private function validateOrderData($data): void
    {
        if (empty($data['phone_number'])) {
            $this->addErrorMessage('Телефон не може бути порожнім.');
        }
        if (empty($data['address'])) {
            $this->addErrorMessage('Адреса не може бути порожньою.');
        }
        if (empty($data['payment_method']) || !in_array($data['payment_method'], ['credit_card', 'cash', 'paypal'])) {
            $this->addErrorMessage('Неправильний метод оплати.');
        }
        if (empty($data['total_amount']) || !is_numeric($data['total_amount']) || $data['total_amount'] <= 0) {
            $this->addErrorMessage('Загальна сума повинна бути позитивним числом.');
        }
    }
}