<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Product;
use models\ProductImages;
use models\Users;

class ProductController extends Controller
{
    public function actionCategory($type): array
    {
        if (!empty($type[0])) {
            $products = Product::findByType($type[0]);
            if (!empty($products)) {
                usort($products, function ($a, $b) {
                    return $b['quantity'] - $a['quantity'];
                });
                $productIds = array_column($products, 'product_id');
                $photos = ProductImages::getAllPhotosByProductIds($productIds);
                return $this->render(null, ['products' => $products, 'photos' => $photos]);
            } else {
                $this->redirect("/product/error404");
                return $this->render();
            }
        } else {
            $this->addErrorMessage('Оберіть категорію');
            return $this->render();
        }
    }

    public function actionIndex(): array
    {
        $laptops = Product::findByType('laptop');
        $phones = Product::findByType('phone');
        $tvs = Product::findByType('tv');
        if (!empty($laptops)) {
            usort($laptops, function ($a, $b) {
                return $b['quantity'] - $a['quantity'];
            });
        }
        if (!empty($phones)) {
            usort($phones, function ($a, $b) {
                return $b['quantity'] - $a['quantity'];
            });
        }
        if (!empty($tvs)) {
            usort($tvs, function ($a, $b) {
                return $b['quantity'] - $a['quantity'];
            });
        }

        $laptops = array_slice($laptops, 0, 4);
        $phones = array_slice($phones, 0, 4);
        $tvs = array_slice($tvs, 0, 4);

        $laptopIds = array_column($laptops, 'product_id');
        $phoneIds = array_column($phones, 'product_id');
        $tvIds = array_column($tvs, 'product_id');

        $laptopPhotos = \models\ProductImages::getAllPhotosByProductIds($laptopIds);
        $phonePhotos = \models\ProductImages::getAllPhotosByProductIds($phoneIds);
        $tvPhotos = \models\ProductImages::getAllPhotosByProductIds($tvIds);

        return $this->render(null, [
            'laptops' => $laptops,
            'laptopPhotos' => $laptopPhotos,
            'phones' => $phones,
            'phonePhotos' => $phonePhotos,
            'tvs' => $tvs,
            'tvPhotos' => $tvPhotos
        ]);
    }

    public function actionView($product_id)
    {
        $product = Product::findByID($product_id[0]);
        if ($product) {
            $photos = \models\ProductImages::getPhotosByProductId($product['product_id']);
            return $this->render(null, ['product' => $product, 'photos' => $photos]);
        } else {
            $this->addErrorMessage('Товар не знайдено');
            return $this->render();
        }
    }

    public function actionError404(): array
    {
        return $this->render();
    }

    public function actionError403(): array
    {
        http_response_code(403);
        return $this->render();
    }

    public function actionCreatesuccess(): array
    {
        return $this->render();
    }

    public function actionList(): array
    {
        $findName = $this->post->FindName;
        if (!empty($findName)) {
            $products = Product::findByName($findName);
            if (!empty($products)) {
                usort($products, function ($a, $b) {
                    return $b['quantity'] - $a['quantity'];
                });
            }
            if (!empty($products)) {
                $productIds = array_column($products, 'product_id');
                $photos = ProductImages::getAllPhotosByProductIds($productIds);
                return $this->render(null, ['products' => $products, 'photos' => $photos]);
            } else {
                $this->addErrorMessage('Товар не знайдено');
                return $this->render();
            }
        } else {
            $this->addErrorMessage('Введіть назву для пошуку');
            return $this->render();
        }
    }

    public function actionDelete($id): array
    {
        if (Core::isUserAdmin()) {
            Product::deleteProduct($id[0]);
            return $this->render();
        } else $this->redirect('/');
    }

    public function actionUpdate($product_id): ?array
    {
        if (Core::isUserAdmin()) {
            $product = Product::findByID($product_id[0]);
            if ($product) {
                if ($this->isPost) {
                    $photos = $_FILES['photos'];

                    $name = $this->post->name;
                    $price = $this->post->price;
                    $type = $this->post->type;
                    $characteristics = $this->post->characteristics;
                    $quantity = $this->post->quantity;
                    if (strlen($name) === 0) {
                        $this->addErrorMessage('Назва товару не вказана');
                    }
                    if (!is_numeric($price) || $price <= 0) {
                        $this->addErrorMessage('Невірно вказана ціна');
                    }
                    if (strlen($type) === 0) {
                        $this->addErrorMessage('Тип товару не вказаний');
                    }
                    if (strlen($characteristics) === 0) {
                        $this->addErrorMessage('Характеристики товару не вказані');
                    } else {
                        $characteristicsArray = [];
                        $pairs = explode(',', $characteristics);
                        foreach ($pairs as $pair) {
                            list($key, $value) = explode(':', $pair);
                            $characteristicsArray[trim($key)] = trim($value);
                        }
                        $characteristicsJson = json_encode($characteristicsArray);

                        if ($characteristicsJson === false) {
                            $this->addErrorMessage('Не вірний формат введених даних. Вони повинні виглядати так: Хар-ка1: Значення1, Хар-ка2: Значення2');
                        }
                    }
                    if (!is_numeric($quantity) || $quantity < 0) {
                        $this->addErrorMessage('Невірно вказана кількість товару');
                    }
                    if (!empty($photos['name'][0])) {
                        $result = ProductImages::uploadPhotos($product_id[0], $photos);
                        $uploadedPhotoUrls = $result['uploaded'];
                        $errors = $result['errors'];

                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                $this->addErrorMessage($error);
                            }
                        }
                    }
                    if (!$this->isErrorMessagesExist()) {
                        Product::updateProduct($product_id[0], $name, $price, $type, $characteristicsJson, $quantity);
                        $this->redirect('/product/view/' . $product_id[0]);
                    }
                }
                return $this->render(null, ['product' => $product]);
            } else {
                $this->addErrorMessage('Товар не знайдено');
                return $this->render();
            }
        } else {
            $this->redirect('/');
        }
    }

    public function actionCreate(): ?array
    {
        if (Core::isUserAdmin()) {
            if ($this->isPost) {
                $name = $this->post->name;
                $price = $this->post->price;
                $type = $this->post->type;
                $characteristics = $this->post->characteristics;
                $quantity = $this->post->quantity;

                if (strlen($name) === 0) {
                    $this->addErrorMessage('Назва товару не вказана');
                }
                if (!is_numeric($price) || $price <= 0) {
                    $this->addErrorMessage('Невірно вказана ціна');
                }
                if (strlen($type) === 0) {
                    $this->addErrorMessage('Тип товару не вказаний');
                }
                if (strlen($characteristics) === 0) {
                    $this->addErrorMessage('Характеристики товару не вказані');
                } else {
                    $characteristicsArray = [];
                    $pairs = explode(',', $characteristics);
                    foreach ($pairs as $pair) {
                        list($key, $value) = explode(':', $pair);
                        $characteristicsArray[trim($key)] = trim($value);
                    }
                    $characteristicsJson = json_encode($characteristicsArray);

                    if ($characteristicsJson === false) {
                        $this->addErrorMessage('Не вірний формат введених даних. Вони повинні виглядати так: Хар-ка1: Значення1, Хар-ка2: Значення2');
                    }
                }
                if (!is_numeric($quantity) || $quantity < 0) {
                    $this->addErrorMessage('Невірно вказана кількість товару');
                }
                if (!$this->isErrorMessagesExist()) {
                    var_dump($characteristicsJson);
                    Product::createProduct($name, $price, $type, $characteristicsJson, $quantity);
                    $this->redirect('/product/createsuccess');
                }
            }
            return $this->render();
        } else
            $this->redirect('/product/error403');
    }
}