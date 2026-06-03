<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos los datos
    $nombre    = $_POST['nombre'] ?? '';
    $telefono  = $_POST['telefono'] ?? '';
    $correo    = $_POST['correo'] ?? '';
    $inicio    = $_POST['fecha_inicio'] ?? date('Y-m-d'); // Si está vacío, pone hoy por defecto
    $fin       = $_POST['fecha_fin'] ?? date('Y-m-d');    // Si está vacío, pone hoy por defecto

    try {
        $sql = "INSERT INTO clientes_mensuales (nombre, telefono, correo, fecha_inicio, fecha_final) 
                VALUES (:nombre, :tel, :correo, :inicio, :fin)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':tel'    => $telefono,
            ':correo' => $correo,
            ':inicio' => $inicio,
            ':fin'    => $fin
        ]);
        
        echo "<script>alert('Cliente registrado con éxito'); window.location.href = 'index.php';</script>";
        
    } catch (PDOException $e) {
        // Mostramos el error real de la base de datos para ver qué está pasando
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>