<?php

require_once DOC_ROOT . '/app/models/main_model.php';
require_once DOC_ROOT . '/app/models/csv_model.php';


class Main_Controller
{
    public $mainModel;
    public $csvModel;
    public $view;
    public $data;

    private $csv;

    public function __construct()
    {
        $this->view = new View();
        $this->mainModel = new Main_Model();
        $this->csvModel = new CSV_Model();
    }

    public function actionIndex()
    {
        $this->data['products'] = $this->mainModel->getData();
        if (isset($_SESSION['msg'])) {
            $this->data['msg'] = $_SESSION['msg'];
            session_unset();
        }

        $this->view->generate('main_view.php', 'template_view.php', $this->data);
    }

    public function actionUpload()
    {
        $this->csv = $this->csvModel->uploadFile();

        if ($this->csv) {
            $products = $this->csvModel->parseCSV($this->csv);

            if (is_array($products)) {
                $_SESSION['msg'] = $this->mainModel->insertProducts($products);

            } else {

                if (is_string($products)) {
                    $_SESSION['msg'] = $products;
                } else {
                    $_SESSION['msg'] = 'Ошибка разбора файла';
                }

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