<?php

namespace App\Controllers;

include __DIR__ . '/../models/models.php';

use App\Models\ModelsClass;

class CarControllers
{

    private static $models;
    private static $host = "localhost";
    private static $dbname = "php_test";
    private static $username = "root";
    private static $password = "";

    function __construct()
    {
        self::$models = new ModelsClass;
        self::$models->conection(self::$host, self::$dbname, self::$username, self::$password);
    }
    
    public function createCars()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $data = file_get_contents('php://input');
            return self::$models->createCars(json_decode($data, false));
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }

    public function listCars($page = 0, $offset = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return self::$models->selectCars($page);
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }


    public function updateCars()
    {
        if ($_SERVER['REQUEST_METHOD'] == "PUT") {
            $data = file_get_contents('php://input');
           return self::$models->updateCar(json_decode($data, false));
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }

    public function deleteCars(){
        if($_SERVER['REQUEST_METHOD'] == "DELETE"){
            $data = file_get_contents('php://input');
           return self::$models->deleteCar(json_decode($data, false));   
        } else{
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }

    public function getCarById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return self::$models->selectCarById($id);
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }

    public function listColors()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return self::$models->selectColors();
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }
    public function getAllCars(){
        
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return self::$models->selectCarAllCars();
        } else {
            return ["error" => "Metodo de requerimento não permitido"];
        }
    }
}
