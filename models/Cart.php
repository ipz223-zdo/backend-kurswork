<?php

namespace models;

use core\Model;
use core\DB;

class Cart extends Model
{
    private $items = [];

    public function __construct()
    {
        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        }
    }

    public function addProduct($productId, $quantity = 1)
    {
        $product = Product::findById($productId);
        if ($product && $product['quantity'] >= $quantity) {
            if (isset($this->items[$productId])) {
                $newQuantity = $this->items[$productId] + $quantity;
                if ($newQuantity > $product['quantity']) {
                    throw new \Exception("Не можна додати більше товару ніж є в наявності.");
                }
                $this->items[$productId] = $newQuantity;
            } else {
                $this->items[$productId] = $quantity;
            }
            $this->save();
        } else {
            throw new \Exception("Не достатньо товарів в наявності");
        }
    }

    public function removeProduct($productId)
    {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }
        $this->save();
    }

    public function increaseQuantity($productId)
    {
        $product = Product::findById($productId);
        if (isset($this->items[$productId]) && $product['quantity'] > $this->items[$productId]) {
            $this->items[$productId]++;
            $this->save();
        }
    }

    public function decreaseQuantity($productId)
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId]--;
            if ($this->items[$productId] <= 0) {
                unset($this->items[$productId]);
            }
            $this->save();
        }
    }

    public function getItems()
    {
        $products = [];
        foreach ($this->items as $productId => $quantity) {
            $product = Product::findById($productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $products[] = $product;
            }
        }
        return $products;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->items as $productId => $quantity) {
            $product = Product::findById($productId);
            if ($product) {
                $total += $product['price'] * $quantity;
            }
        }
        return $total;
    }

    public function save(): void
    {
        $_SESSION['cart'] = $this->items;
    }

    public function clear()
    {
        $this->items = [];
        $this->save();
    }

    public function getQuantity($productId)
    {
        return $this->items[$productId[0]] ?? 0;
    }
}