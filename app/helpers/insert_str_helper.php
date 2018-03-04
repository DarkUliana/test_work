<?php


class Insert_Str_Helper
{
    public function warehousesUnique($array, $column)
    {
        $warehouses = [];

        foreach ($array as $value) {
            $warehouses[] = $value[$column];
        }
        $warehouses = array_unique($warehouses);

        return $warehouses;
    }

    public function whereWarehousesStr($array, $column)
    {

        $where = "WHERE ";
        for ($i = 0; $i < count($array); $i++) {
            $where .= "$column = '{$array[$i]}'";

            if ($i < count($array) - 1) {
                $where .= " OR ";
            }
        }

        return $where;
    }


    public function changeWarehouseNameToId($array, $ids)
    {

        $newArr = [];
        $assocIds = [];
        foreach ($ids as $value) {
            $assocIds[$value['warehouse']] = $value['id'];
        }

        for ($i = 0; $i < count($array); $i++) {

            if (isset($assocIds[$array[$i]['warehouse']])) {

                $newArr[$i] = $array[$i];
                $newArr[$i]['warehouse_id'] = (int)$assocIds[$array[$i]['warehouse']];
                unset($newArr['warehouse']);
            }

        }

        return $newArr;
    }


}