<?php

require_once DOC_ROOT . '/system/driver.php';

class Main_Model
{
    public $connection;
    public $table = 'products';
    public $product = 'product';
    public $quantity = 'quantity';
    public $warehouse = 'warehouse';

    public function __construct()
    {
        $this->connection = new Driver();
    }

    public function getData()
    {
        $query = "SELECT $this->product, sum, GROUP_CONCAT($this->warehouse SEPARATOR ', ') as warehouses
                  FROM
                  (SELECT $this->product, SUM($this->quantity) AS sum, $this->warehouse
                  FROM $this->table  
                  GROUP BY $this->warehouse, $this->product) as prods
                  WHERE sum > 0
                  GROUP BY $this->product";
        $result = $this->connection->run($query);
        return $result;
    }

    public function uploadFile()
    {
        $file = CSV_ROOT . '/file_' . date('Ymd_His') . '.csv';
        if (move_uploaded_file($_FILES['csv']['tmp_name'], $file)) {
            return $file;
        }

        return null;
    }

    public function parse($path)
    {
        $file = fopen($path, 'r');

        $array = [];
        $count = false;
        while (($line = fgetcsv($file)) !== FALSE) {
            if (!$count) {
                $count = true;
                continue;
            }
            if ($this->check($line))
                $array[] = $line;
        }
        fclose($file);
        @unlink($path);
        return $array;
    }


    public function check($data)
    {
        if (!$data || count($data) < 2) {
            return false;
        }

        foreach ($data as $one) {
            if (!$one)
                return false;
        }
        return true;
    }

    public function insertProducts($data)
    {

        if (!is_array($data)) {
            return 'Ошибка при разборе файла!';
        }

        $value = $this->valuesToStr($data);

        $query = "INSERT INTO $this->table ($this->product, $this->quantity, $this->warehouse) " .
                 "VALUES $value";

        return $this->connection->run($query);
    }

    private function valuesToStr($data)
    {
        $value = '';
        foreach ($data as $item) {
            $value .= "('" . implode("','", $item) . "')" . ",";
        }
        $value = substr($value, 0, -1);
        return $value;
    }

}