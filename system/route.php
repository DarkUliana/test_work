<?php

class Route
{
    static $model_name = 'Main_Model';
    static $controller_name = 'Main_Controller';
    static $action_name = 'actionIndex';

    static function start()
    {
        $routes = str_replace('/', '', $_SERVER['REQUEST_URI']);

        if ($routes == '' || $routes == '/' || $routes == 'upload') {

            $model_file = strtolower(self::$model_name) . '.php';
            include "app/models/" . $model_file;

            $controller_file = strtolower(self::$controller_name) . '.php';
            include "app/controllers/" . $controller_file;


            $controller = new self::$controller_name;
            $action = self::$action_name;

            if ($routes == 'upload') {
                $action = 'actionUpload';
            }

            $controller->$action();


        } else {
            Route::ErrorPage404();
        }

    }

    function ErrorPage404()
    {
        $controller_file = strtolower(self::$controller_name) . '.php';
        include "app/controllers/" . $controller_file;
        $controller = new self::$controller_name;
        $controller->page404();

    }
}