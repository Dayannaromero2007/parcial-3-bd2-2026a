<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $_POST['nombre'] ?? '';
    $placa     = $_POST['placa'] ?? '';
    $tipo_id   = $_POST['tipo_vehiculo'] ?? '';
    $telefono  = $_POST['telefono'] ?? '';
    $correo    = $_POST['correo'] ?? '';
    $inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-d');
    $fin    = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : date('Y-m-d');

    try {
        // Iniciamos una transacción: o se guarda todo (cliente + vehículo), o no se guarda nada.
        $conexion->beginTransaction(); 

        // 1. Guardar el cliente en clientes_mensuales
        $sql_cli = "INSERT INTO clientes_mensuales (nombre, telefono, correo, fecha_inicio, fecha_final) 
                    VALUES (:nombre, :tel, :correo, :inicio, :fin)";
        $stmt_cli = $conexion->prepare($sql_cli);
        $stmt_cli->execute([
            ':nombre' => $nombre,
            ':tel'    => $telefono,
            ':correo' => $correo,
            ':inicio' => $inicio,
            ':fin'    => $fin
        ]);
        
        // Obtenemos el ID del cliente que se acaba de crear
        $id_cliente_nuevo = $conexion->lastInsertId(); 

        // 2. Guardar el vehículo asociándolo al cliente
        // Asegúrate de que la columna Tipo_vehiculo_idTipo_vehiculo se llame exactamente así en tu tabla
        $sql_veh = "INSERT INTO vehiculo (placa, Tipo_vehiculo_idTipo_vehiculo, idClientes_mensuales) 
                    VALUES (:placa, :tipo, :id_cli)";
        $stmt_veh = $conexion->prepare($sql_veh);
        $stmt_veh->execute([
            ':placa' => $placa,
            ':tipo'  => $tipo_id,
            ':id_cli'=> $id_cliente_nuevo
        ]);

        // Confirmamos que todo salió bien
        $conexion->commit(); 
        
        echo "<script>alert('Cliente y vehículo registrados con éxito'); window.location.href = 'index.php';</script>";

    } catch (PDOException $e) {
        // Si hay error, deshacemos los cambios para que no queden datos a medias
        $conexion->rollBack();
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>