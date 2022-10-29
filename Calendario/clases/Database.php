<?php
require_once '.env.php';
class Database {
    private $conexion;

    public function __construct()
    {
        if (is_null($this->conexion)) {
            $credenciales = credenciales();
            $this->conexion = new mysqli(
                $credenciales['servidor'],
                $credenciales['usuario'],
                $credenciales['clave'],
                $credenciales['base_de_datos'],
            );
            if ($this->conexion->connect_error) {
                $error = 'Error de conexión: ' . $this->conexion->connect_error;
                $this->conexion = null;
                die($error);
            }
            $this->conexion->set_charset('utf8');
        }
   }

   public function obtenerConexion() {
    return $this->conexion;
}
}