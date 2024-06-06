<?php

namespace core;

class Model
{
    protected array $fieldsArray;
    protected static string $primaryKey = "id";
    protected static string $tableName = '';

    public function __construct()
    {
        $this->fieldsArray = [];
    }

    public function __set($name, $value)
    {
        $this->fieldsArray[$name] = $value;
    }

    public function __get($name)
    {
        return $this->fieldsArray[$name];
    }

    public static function deleteByID($id): void
    {
        Core::get()->db->delete(static::$tableName, [static::$primaryKey => $id]);
    }

    public static function deleteByCondition($conditionAssocArray): void
    {
        Core::get()->db->delete(static::$tableName, $conditionAssocArray);
    }

    public static function findByID($id)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0)
            return $arr [0];
        else
            return null;
    }

    public static function findByCondition($conditionAssocArray): false|array|null
    {
        $arr = Core::get()->db->select(static::$tableName, '*', $conditionAssocArray);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public function save(): void
    {
        $isInsert = false;
        if(!isset($this->{static::$primaryKey}))
            $isInsert = true;
        else
        {
            $value = $this->{static::$primaryKey};
            if (empty($value))
                $isInsert = true;
        }
        if ($isInsert) {
            //insert
            Core::get()->db->insert(static::$tableName, $this->fieldsArray);
        } else {
            //update
            Core::get()->db->update(static::$tableName, $this->fieldsArray, [
                static::$primaryKey => $this->{static::$primaryKey}
            ]);
        }
    }
}