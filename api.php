<?php

include 'App/controllers/CarControllers.php';

use App\Controllers\CarControllers;

class Api
{
    private static $carControllers;
    function __construct()
    {
        self::$carControllers = new CarControllers;
    }

    public function callEndpoint($endpoint)
    {
        switch ($endpoint) {

            case 'create-cars':
                self::createCarsEndpoint();
                break;
            case 'get-car-by-id':
                self::getCarByIdEndpoint();
                break;

            case 'list-cars':
                self::getCarsEndpoint();
                break;
            case 'update-cars':
                self::updateCarsEndpoint();
                break;
            case 'delete-cars':
                self::deleteCarsEndpoint();
                break;
            case 'list-colors':
                self::listColorsEndpoint();
                break;
            case 'get-all-cars':
                self::getAllCarsEndpoint();
                break;
            default:
                echo json_encode(["error" => "Endpoint não Encontrada"]);
                break;
        }
    }
    public static function createCarsEndpoint()
    {
        echo json_encode(self::$carControllers->createCars());
    }

    public static function getCarsEndpoint()
    {
        $page = $_REQUEST['page'];
        //CarController::listCars();
        echo json_encode(self::$carControllers->listCars($page));
    }

    public static function updateCarsEndpoint()
    {
        echo json_encode(self::$carControllers->updateCars());
    }

    public static function deleteCarsEndpoint()
    {
        echo json_encode(self::$carControllers->deleteCars());
    }
    public static function getCarByIdEndpoint(){
        
        $id = $_REQUEST['id'];
        echo json_encode(self::$carControllers->getCarById($id));
    }
    public static function listColorsEndpoint(){
        echo json_encode(self::$carControllers->listColors());
    }
    public static function getAllCarsEndpoint(){
        echo json_encode(self::$carControllers->getAllCars());
    }
}
