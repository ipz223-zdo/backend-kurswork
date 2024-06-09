<?php

namespace models;

use core\Core;
use core\Model;

class ProductImages extends Model
{
    protected static string $primaryKey = 'image_id';
    protected static string $tableName = 'ProductImages';

    public function __construct()
    {
        parent::__construct();
    }

    public static function uploadPhotos($product_id, $files)
    {
        $uploadedPhotoUrls = [];
        $errors = [];

        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $unique_photo_name = uniqid() . '.jpg';
            $upload_directory = './uploads/';
            $destination = $upload_directory . $unique_photo_name;

            if (move_uploaded_file($tmp_name, $destination)) {
                $photo = new ProductImages();
                $photo->product_id = $product_id;
                $photo->image_url = $unique_photo_name;
                $photo->save();

                $uploadedPhotoUrls[] = $destination;
            } else {
                $errors[] = "Failed to upload file: " . $files['name'][$key];
            }
        }

        return ['uploaded' => $uploadedPhotoUrls, 'errors' => $errors];
    }
    public static function deletePhotosByProductId($product_id)
    {
        $photos = self::findByCondition(['product_id' => $product_id]);
        foreach ($photos as $photo) {
            $filePath = './uploads/' . $photo['image_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            self::deleteByID($photo['image_id']);
        }
    }

    public static function deletePhotoById($image_id)
    {
        $photo = self::findByID($image_id);
        if ($photo) {
            $filePath = './uploads/' . $photo['image_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            self::deleteByID($image_id);
        }
    }

    public static function getPhotosByProductId($product_id): false|array
    {
        $db = \core\Core::get()->db;
        $photos = $db->select('ProductImages', '*', ['product_id' => $product_id]);
        return $photos;
    }

    public static function getAllPhotosByProductIds($productIds): array
    {
        $photos = [];
        foreach ($productIds as $productId) {
            $photos[$productId] = self::getFirstPhotoByProductId($productId);
        }
        return $photos;
    }

    public static function getFirstPhotoByProductId($product_id)
    {
        $photos = self::findByCondition(['product_id' => $product_id]);
        return !empty($photos) ? $photos[0] : null;
    }
}