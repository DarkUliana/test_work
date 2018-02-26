<?php

require_once DOC_ROOT . '/app/models/main_model.php';

class Main_Controller
{
    public $model;
    public $view;
    public $data;

    private $csv;

    public function __construct()
    {
        $this->view = new View();
        $this->model = new Main_Model();
    }

    public function actionIndex()
    {
        $this->data['products'] = $this->model->getData();
        if (isset($_SESSION['msg'])) {
            $this->data['msg'] = $_SESSION['msg'];
            session_unset();
        }

        $this->view->generate('main_view.php', 'template_view.php', $this->data);
    }

    public function actionUpload()
    {
        $this->csv = $this->model->uploadFile();

        if ($this->csv) {
            $products = $this->model->parse($this->csv);

            if ($products) {
                $_SESSION['msg'] = $this->model->insertProducts($products);

            } else {
                $_SESSION['msg'] = 'Ошибка разбора файла';
            }
        } else {
            $_SESSION['msg'] = 'Ошибка загрузки файла';
        }

        header("Location: /");
    }

    /**
     * @return mixed
     */
    public function page404()
    {
        header('HTTP/1.1 404 Not Found');
        $this->view->generate('404_view.php', 'template_view.php');
    }


}