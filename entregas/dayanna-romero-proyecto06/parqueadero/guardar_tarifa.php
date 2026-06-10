<?php
require_once 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_v = $_POST['tipo_vehiculo'];
    $precio_hora = $_POST['precio_hora'];
    
    // Suponiendo que tienes una tabla Tarifas
    $sql = "UPDATE Tarifas SET precio_hora = ? WHERE tipo_vehiculo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$precio_hora, $tipo_v]);
    
    header("Location: index.php?seccion=tarifas");
}
?>