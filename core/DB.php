<?php

namespace core;

use PDO;

class DB
{
    public $pdo;

    public function __construct($host, $name, $login, $password)
    {
        $this->pdo = new PDO("mysql:host={$host};dbname={$name};",
            $login, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
    }

    //This is a test version for now
    public function select($table, $fields = "*", $where = null) //
    {
        if (is_array($fields))
            $fields_string = implode(',', $fields);
        else
            if (is_string($fields))
                $fields_string = $fields;
            else
                $fields_string = "*";
        if (is_array($where)) {
            $where_string = "WHERE ";
            $where_fields = array_keys($where);
            $parts = [];
            foreach ($where_fields as $field){
                $parts [] = "{$field} = :{$field}";
                $where_string .= implode(' AND ', $parts);
            }
        } else
            if (is_string($where))
                $where_string = $where;
            else
                $where_string = "";

        $sql = "SELECT {$fields_string} FROM {$table} {$where_string}";
        $sth = $this->pdo->prepare($sql);
        foreach ($where as $key => $value)
            $sth->bindValue(":{$key}", $value);
        $sth->execute();
        return $sth->fetchAll();
    }
}