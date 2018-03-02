<?php


class Insert_Str_Helper
{

    public function whereWarehousesStr($array, $column)
    {
        $warehouses = [];

        foreach ($array as $value) {
            $warehouses[] = $value[$column];
        }
        $warehouses = array_unique($warehouses);

        $where = "WHERE ";
        for ($i = 0; $i < count($warehouses); $i++) {
            $where .= "$column = '{$array[$i][$column]}'";

            if($i < count($warehouses)-1) {
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