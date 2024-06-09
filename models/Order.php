<?php

namespace models;

use core\Core;
use core\Model;

class Order extends Model
{
    protected static string $tableName = 'orders';
    protected static string $primaryKey = 'order_id';

    public static function findAll(): array
    {
        $db = Core::get()->db;
        $results = $db->select(self::$tableName);

        $orders = [];
        foreach ($results as $result) {
            $order = self::createOrderFromResult($result);
            $orders[] = $order;
        }

        return $orders;
    }

    public static function findOne($orderId): ?Order
    {
        $db = Core::get()->db;
        $result = $db->select(self::$tableName, '*', [self::$primaryKey => $orderId]);

        if ($result) {
            return self::createOrderFromResult($result[0]);
        } else {
            return null;
        }
    }

    public static function findByUserId($userId)
    {
        $db = Core::get()->db;
        $results = $db->select(self::$tableName, '*', ['user_id' => $userId]);
        $orders = [];

        foreach ($results as $result) {
            $order = self::createOrderFromResult($result);
            $orders[] = $order;
        }

        return $orders;
    }

    private static function createOrderFromResult($result)
    {
        $order = new Order();
        $order->order_id = $result['order_id'];
        $order->user_id = $result['user_id'];
        $order->phone_number = $result['phone_number'];
        $order->address = $result['address'];
        $order->payment_method = $result['payment_method'];
        $order->total_amount = $result['total_amount'];
        $order->status = $result['status'];
        if ($result['products_json'] !== null) {
            $order->products_json = json_decode($result['products_json'], true);
        } else {
            $order->products_json = [];
        }
        $order->created_at = $result['created_at'];
        $order->updated_at = $result['updated_at'];
        $order->payment_status = $result['payment_status'];
        return $order;
    }
}