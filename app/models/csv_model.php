<?php

class CSV_Model
{
    public function uploadFile()
    {
        $file = CSV_ROOT . '/file_' . date('Ymd_His') . '.csv';
        if (move_uploaded_file($_FILES['csv']['tmp_name'], $file)) {
            return $file;
        }

        return null;
    }
    public function parseCSV($path)
    {
        $file = fopen($path, 'r');

        $array = [];
        $keys = [];
        $count = false;
        while (($line = fgetcsv($file)) !== FALSE) {

            if ($this->checkCSV($line)) {
                if (!$count) {
                    $count = true;
                    $keys = $line;
                    continue;
                }
                $array[] = $line;
            }
        }
        fclose($file);
        @unlink($path);

        if(empty($array)) {
            return "Пустой файл";
        }

        if (empty($keys)) {
            return "Нет названий столбцов";
        }

        if(count($keys) != count($array[0])) {
            return 'Недостаточное или слишком большое количество названий столбцов в файле';
        }

        return $this->arrToAssocArr($array, $keys);
    }

    private function arrToAssocArr($array, $keys)
    {
        $assocArr = [];
        foreach ($array as $value) {

            $tempArr = [];
            for ($i = 0; $i < count($keys); $i++) {
                $tempArr[$keys[$i]] = $value[$i];
            }
            $assocArr[] = $tempArr;
        }

        return $assocArr;
    }


    protected function checkCSV($data)
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
}