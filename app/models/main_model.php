<?php

require_once DOC_ROOT . '/system/driver.php';
require_once DOC_ROOT . '/app/helpers/insert_str_helper.php';

class Main_Model
{
    public $connection;
    public $productsTable = 'products';
    public $warehosesTable = 'warehouses';


    public function __construct()
    {
        $this->connection = new Driver();
    }

    public function getData()
    {
        $query = "SELECT p.product, SUM(p.quantity) AS sum, GROUP_CONCAT(w.warehouse SEPARATOR ', ') AS wh
                  FROM $this->productsTable as p
                  LEFT JOIN $this->warehosesTable AS w ON p.warehouse_id = w.id 
                  WHERE quantity > 0
                  GROUP BY product";
        $result = $this->connection->run($query);

        if ($result) {
            return $result;
        }
        return [];
    }

    public function insertProducts($data)
    {

        if (!is_array($data)) {
            return 'Ошибка при разборе файла!';
        }
        $helper = new Insert_Str_Helper();

        $insertWarehouses = $helper->warehousesUnique($data, "warehouse");
        $this->insertWarehouses($insertWarehouses);

        $where = $helper->whereWarehousesStr($insertWarehouses, "warehouse");
        $whId = $this->selectWarehouseIdByName($where);
        $newData = $helper->changeWarehouseNameToId($data, $whId);

        foreach ($newData as $value) {

            $whereForOne = "WHERE product = '{$value['product']}' AND warehouse_id = {$value['warehouse_id']}";
            $existQuery = "SELECT * FROM $this->productsTable $whereForOne";
            $ifExist = $this->connection->run($existQuery);
            if ($ifExist) {
                $query = "UPDATE $this->productsTable 
                          SET quantity = quantity+{$value['quantity']} 
                          $whereForOne";
            } else {
                $query = "INSERT INTO $this->productsTable
                          (product, quantity, warehouse_id)
                          VALUES ('{$value['product']}', {$value['quantity']}, {$value['warehouse_id']})";
            }
//            var_dump($query); die();
            $this->connection->run($query);

        }

        return 1;
    }

    public function selectWarehouseIdByName($where)
    {
        $query = "SELECT * FROM warehouses $where";

        $result = $this->connection->run($query);
        if ($result) {
            return $result;
        }
        return [];
    }

    public function insertWarehouses($warehouses)
    {
        $warehousesStr = "('" . implode("'),('", $warehouses) . "')";

        $query = "INSERT IGNORE INTO $this->warehosesTable
                  (warehouse) VALUES $warehousesStr ";
//        var_dump($query); die();

        $this->connection->run($query);
    }


}