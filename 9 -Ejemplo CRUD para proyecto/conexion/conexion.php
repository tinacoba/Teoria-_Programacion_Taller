<?php
class Conexion{
    private $servidor = "localhost";
    private $usuario = "root";
    private $password = "";
    private $baseDatos = "biblioteca";
    private $conexion;

    public function conectar() {
        $this->conexion = new mysqli($this->servidor,$this->usuario,$this->password,$this->baseDatos );      
       if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        return $this->conexion; 
    }
}
?>
