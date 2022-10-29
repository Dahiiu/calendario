<?php

require_once "./clases/Database.php";

class Eventos
{

    protected $conexion;
    public function __construct()
    {
        $Database = new Database();
        $this->conexion = $Database->obtenerConexion();
    }

    public function listar()
    {
        $result = $this->conexion->query("SELECT * FROM eventos");
        $events_formatted = [];
        while ($event = $result->fetch_assoc()) {
            $events_formatted[] = [
                "id" => $event['id'],
                "title" => $event['titulo'],
                "start" => $event['inicio'],
                "end" => $event['fin'],
                "description" => $event['descripcion'],
                "backgroundColor" => $event['colorfondo'],
                "textColor" => $event['colortexto'],
            ];
        }
        return json_encode($events_formatted);
    }


    public function agregar($data)
    {
        $result = $this->conexion->query("INSERT INTO eventos(titulo, descripcion, inicio, fin, colortexto, colorfondo) VALUES 
    ('" . $data['titulo'] . "','" . $data['descripcion'] . "','" . $data['inicio'] . "','" . $data['fin'] . "','" . $data['colortexto'] . "','" . $data['colorfondo'] . "')");
        if ($result) {
            header('Location: home.php');
        }
    }


    public function modificar($data)
    {
        $sql = "UPDATE eventos SET ";

        $id = $data["id"];

        foreach ($data as $key => $value) {
            if ($key == 'id') {
                continue;
            }
            $sql .= strtolower($key) . " = '" . $value . "',";
        }

        $sql = rtrim($sql, ",");
        $sql .= " WHERE id = " . $id;
        $this->conexion->query($sql);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM eventos WHERE id = $id";
        $this->conexion->query($sql);
    }
    
}
