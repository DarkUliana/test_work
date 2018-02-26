<?php

class Driver
{

    private $host;
    private $user;
    private $password;
    private $dbName;
    private $connection;


    public function __construct()
    {
        require_once DOC_ROOT . "/config.php";
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['pass'];
        $this->dbName = $config['dbName'];

    }

    public function load()
    {
        if ($this->connect())
            $this->disconnect();

    }

    public function connect()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if ($this->connection->connect_errno) {

            die("Установить соединение с базой данных не удалось <br>" . $this->connection->connect_errno);
        }
        return true;
    }

    public function disconnect()
    {
        $this->connection->close();
    }

    public function run($query = "")
    {
        if (!$query) {
            return false;
        }

        $this->connect();
        $answer = $this->connection->query($query);
        $this->disconnect();

        if (is_object($answer)) {

            $array = [];
            while ($row = $answer->fetch_assoc()) {

                $array[] = $row;
            }
            $answer->free();
            return $array;
        }

        return $answer;
    }

}