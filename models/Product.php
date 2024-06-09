<?php

namespace models;

use core\Core;
use core\Model;

/**
 * @property int $product_id ID
 * @property string $name Назва
 * @property float $price Ціна
 * @property string $type Тип
 * @property array $characteristics Характеристики
 * @property array $comments Коментарі
 * @property int $quantity Кількість
 */
class Product extends Model
{
    public static string $tableName = 'products';
    protected static string $primaryKey = 'product_id';

    public function __set($name, $value)
    {
        if ($name == 'characteristics' && is_array($value)) {
            $value = json_encode($value);
        }
        parent::__set($name, $value);
    }

    public function __get($name)
    {
        $value = parent::__get($name);
        if ($name == 'characteristics' && !empty($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    public static function findByName($name)
    {
        $rows = self::findByCondition(['name' => $name]);
        if (!empty($rows)) {
            return $rows[0];
        }
        return null;
    }

    public static function createProduct($name, $price, $type, $characteristics, $quantity)
    {
        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->type = $type;
        $product->characteristics = $characteristics;
        $product->quantity = $quantity;
        $product->save();
    }

    public static function updateProduct($product_id, $name, $price, $type, $characteristics, $quantity)
    {
        $product = self::findByID($product_id);

        if ($product !== null) {
            $productData = [
                'name' => $name,
                'price' => $price,
                'type' => $type,
                'characteristics' => $characteristics,
                'quantity' => $quantity
            ];

            Core::get()->db->update(static::$tableName, $productData, [static::$primaryKey => $product_id]);

            return true;
        } else {
            return false;
        }
    }

    public static function deleteProduct($product_id)
    {
        self::deleteByID($product_id);
        ProductImages::deletePhotosByProductId($product_id);
    }

    public static function decreaseQuantity($product_id, $quantity): void
    {
        $product = self::findByID($product_id);
        if ($product) {
            $product->quantity -= $quantity;
            $product->save();
        }
    }

    public static function findAll(): array
    {
        $db = Core::get()->db;
        $results = $db->select('products');

        $products = [];
        foreach ($results as $result) {
            $product = new Product();
            $product->product_id = $result['product_id'];
            $product->name = $result['name'];
            $product->price = $result['price'];
            $product->quantity = $result['quantity'];
            $products[] = $product;
        }

        return $products;
    }
}