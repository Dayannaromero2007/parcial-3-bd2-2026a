<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $color = $_POST['color'];
    $tipo = $_POST['tipo_vehiculo'];
    $regimen = $_POST['regimen'];
    
    // VALIDACIÓN IMPORTANTE: 
    // Si el usuario no seleccionó un espacio, forzamos a que el valor sea NULL 
    // para que la base de datos no arroje un error de integridad.
    $espacio_asignado = $_POST['espacio_asignado']; 
    if (empty($espacio_asignado)) {
        $espacio_asignado = null; 
    }
    
    $hora_actual = date('Y-m-d H:i:s'); 
    
    try {
        // 1. Guardamos el vehículo
        $sql_vehiculo = "INSERT IGNORE INTO vehiculo (placa, marca, color, Tipo_vehiculo_idTipo_vehiculo) VALUES (?, ?, ?, ?)";
        $stmt_vehiculo = $conexion->prepare($sql_vehiculo);
        $stmt_vehiculo->execute([$placa, $marca, $color, $tipo]);
        
        // 2. Buscamos el ID numérico
        $sql_buscar_id = "SELECT idVehiculo FROM vehiculo WHERE placa = ?";
        $stmt_buscar = $conexion->prepare($sql_buscar_id);
        $stmt_buscar->execute([$placa]);
        $vehiculo = $stmt_buscar->fetch(PDO::FETCH_ASSOC);
        $id_del_vehiculo = $vehiculo['idVehiculo'];
        
        // 3. Registramos el ingreso 
        $sql_ingreso = "INSERT INTO ingresos (Vehiculo_idVehiculo, fecha_hora_ingreso, tipo_cliente, Espacio_idEspacio) VALUES (?, ?, ?, ?)";
        $stmt_ingreso = $conexion->prepare($sql_ingreso);
        $stmt_ingreso->execute([$id_del_vehiculo, $hora_actual, $regimen, $espacio_asignado]);
        
        echo "<script>alert('¡Vehículo registrado con éxito!'); window.location.href = 'index.php';</script>";
        exit();
        
    } catch(PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
}
// Fin del script. No ponemos '?>' para evitar problemas de salida en blanco.