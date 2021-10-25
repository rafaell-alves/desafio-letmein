<?php

namespace App\Models;

use DateTime;
use Exception;
use PDO;

class ModelsClass
{

    private static $conn;

    public function conection($host, $dbname, $username, $password = "")
    {
        self::$conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);


    }
    public function createCars($data)
    {
        try {
            if (!property_exists($data, "owner")  || !property_exists($data, 'color_id') || !property_exists($data, 'model') || !property_exists($data, "plate")) {
                return ['error' => "Falta informações do veiculo"];
            }
            if ($data->owner == ''  || $data->color_id == '' || $data->model == '' || $data->plate == '') {
                return ['error' => "Falta informações do veiculo"];
            }
            $query = "INSERT INTO vehicles (owner_name,color_id,model,plate) VALUES ('" . $data->owner . "','" . $data->color_id . "','" . $data->model . "','" . $data->plate . "')";
            self::$conn->exec($query);
            return ["success" => "Veiculo criado"];
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel criar o veiculo"];
        }
    }


    public function selectCars($page = 0)
    {

        try {

            $query = "SELECT COUNT(*) from vehicles";
            $total = self::$conn->query($query)->fetchColumn();
            $limit = 10;
            $pages = ceil($total / $limit);
            $offset = ($page - 1) * $limit;
            $data = self::$conn->query("SELECT * FROM vehicles ORDER BY id LIMIT $limit OFFSET $offset");
            $json = ["cars" => [], "total_page" => 0];
            foreach ($data as $row) {
                $colors_select = "SELECT * from color where " . intval($row["color_id"]) . " like id";

                $color = self::$conn->query($colors_select)->fetchColumn(1);

                array_push(
                    $json['cars'],
                    [
                        "id" => $row["id"],
                        "owner_name" => $row["owner_name"],

                        "color_id" => $row["color_id"],
                        "color_name" => $color,
                        "model" => $row["model"],
                        "plate" => $row["plate"],
                        "inserted_at" => $row["inserted_at"],

                        "updated_at" => $row["updated_at"],
                    ]
                );
            }
            $json['total_page'] = $pages;

            return $json;
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel resgatar os veiculos"];
        }
    }
    public function selectCarById($id)
    {
        $query = "SELECT * from vehicles where " . intval($id) . " like id";
        $stmt = self::$conn->query($query);
        $data = $stmt->fetch();
        $colors_select = "SELECT * from color where " . intval($data["color_id"]) . "= id";

        $color = self::$conn->query($colors_select)->fetchColumn(1);
        $json = [
            "id" => $data["id"],
            "owner_name" => $data["owner_name"],
            "color_id" => $data["color_id"],
            "color_name" => $color,
            "model" => $data["model"],
            "plate" => $data["plate"],
            "inserted_at" => $data["inserted_at"],

            "updated_at" => $data["updated_at"]
        ];

        return $json;
    }

    public function updateCar($data)
    {
        try {
            if (!property_exists($data, "owner")  || !property_exists($data, 'color_id') || !property_exists($data, 'model') || !property_exists($data, "plate") ||  !property_exists($data, 'id')) {
                return ['error' => "Falta informações do veiculo"];
            }
           
            $query = "UPDATE vehicles SET owner_name= '" . $data->owner . " ', color_id= '" . $data->color_id . "' , model= '" . $data->model . "' , plate = '" . $data->plate . "' WHERE " . intval($data->id) . " = id";

            self::$conn->exec($query);
            return ["success" => "Veiculo Atualizado"];
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel atualizar o veiculo"];
        }
    }

    public function deleteCar($data)
    {
        try {
            if (!property_exists($data, 'id')) {
                return ['error' => "Erro Falta o ID do Carro"];
            }

            $query = "DELETE FROM vehicles WHERE " . intval($data->id) . " = id";


            self::$conn->exec($query);
            return ["success" => "Veiculo Deletado Com Sucesso"];
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel Deletar o veiculo"];
        }
    }

    public function selectColors()
    {

        try {

            $data = self::$conn->query("SELECT * FROM color ORDER BY name");
            $json = ["colors" => []];
            foreach ($data as $row) {

                array_push(
                    $json['colors'],
                    [
                        "id" => $row["id"],
                        "color_name" => $row["name"],

                    ]
                );
            }


            return $json;
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel listar as cores"];
        }
    }

    public function selectCarAllCars()
    {
        $data = self::$conn->query("SELECT * FROM vehicles ORDER BY id");
        $json = ["cars" => []];
        try {
            foreach ($data as $row) {
                $colors_select = "SELECT * from color where " . intval($row["color_id"]) . " like id";

                $color = self::$conn->query($colors_select)->fetchColumn(1);

                array_push(
                    $json['cars'],
                    [
                        "id" => $row["id"],
                        "owner_name" => $row["owner_name"],

                        "color_id" => $row["color_id"],
                        "color_name" => $color,
                        "model" => $row["model"],
                        "plate" => $row["plate"],
                        "inserted_at" => $row["inserted_at"],

                        "updated_at" => $row["updated_at"],
                    ]
                );
            }


            return $json;
        } catch (\Throwable $th) {
            return ["erro" => "Não foi possivel resgatar os veiculos"];
        }
    }
}
