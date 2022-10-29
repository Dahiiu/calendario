<?php
require_once '.env.php';
require_once 'Usuario.php';
require_once "Database.php";

class RepositorioUsuario
{
    protected $conexion;

    public function __construct()
    {
        $Database = new Database();
        $this->conexion = $Database->obtenerConexion();
    }

   public function login($nombre_usuario, $clave)
   {
       $q = "SELECT id, clave, nombre, apellido FROM usuarios WHERE usuario = ?";
       $query = $this->conexion->prepare($q);
       $query->bind_param("s", $nombre_usuario);

       if ($query->execute()) {
           $query->bind_result($id, $clave_encriptada, $nombre, $apellido);
           if( $query->fetch() ) {
               if ( password_verify($clave, $clave_encriptada) ) {
                   return new Usuario($nombre_usuario, $nombre, $apellido, $id);
               }
           }
       }
       return false;
    }

    public function save(Usuario $usuario, $clave)
    {
       $q = "INSERT INTO usuarios (usuario, nombre, apellido, clave) ";
       $q.= "VALUES (?, ?, ?, ?)";
       $query = $this->conexion->prepare($q);
       $nombre_usuario = $usuario->getUsuario();
       $nombre = $usuario->getNombre();
       $apellido = $usuario->getApellido();
       $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);
       $query->bind_param(
           "ssss",
           $nombre_usuario,
           $nombre,
           $apellido,
           $clave_encriptada
       );
       if ($query->execute()) {
           // Se guardó bien, retornamos el id del usuario
           return $this->conexion->insert_id;
       } else {
           // No se guardó bien, retornamos false
           return false;
       }
    }


}
