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
    
    // 1. Recibimos el espacio que el usuario eligió en el formulario
    $espacio = $_POST['espacio_asignado'] ?? null;
    if (empty($espacio)) {
        $espacio = null;
    }

    try {
        // Iniciamos la transacción (todo o nada)
        $conexion->beginTransaction(); 

        // 2. Guardar el cliente en clientes_mensuales
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
        
        $id_cliente_nuevo = $conexion->lastInsertId(); 

        // 3. Guardar el vehículo (Usamos INSERT IGNORE por si el carro ya existía como ocasional antes)
        $sql_veh = "INSERT IGNORE INTO vehiculo (placa, Tipo_vehiculo_idTipo_vehiculo, idClientes_mensuales) 
                    VALUES (:placa, :tipo, :id_cli)";
        $stmt_veh = $conexion->prepare($sql_veh);
        $stmt_veh->execute([
            ':placa' => $placa,
            ':tipo'  => $tipo_id,
            ':id_cli'=> $id_cliente_nuevo
        ]);

        // Buscamos cuál es el ID del vehículo (lo necesitamos para bloquear el espacio)
        $stmt_buscar = $conexion->prepare("SELECT idVehiculo FROM vehiculo WHERE placa = ?");
        $stmt_buscar->execute([$placa]);
        $vehiculo = $stmt_buscar->fetch(PDO::FETCH_ASSOC);
        $id_vehiculo_nuevo = $vehiculo['idVehiculo'];

        // 4. LÓGICA DEL MAPA: Bloquear el espacio en la tabla ingresos
        if ($espacio !== null) {
            $hora_actual = date('Y-m-d H:i:s');
            // Lo ingresamos con fecha de salida NULL para que el mapa lo pinte de morado
            $sql_ingreso = "INSERT INTO ingresos (Vehiculo_idVehiculo, fecha_hora_ingreso, tipo_cliente, Espacio_idEspacio) 
                            VALUES (?, ?, 'Mensual', ?)";
            $stmt_ingreso = $conexion->prepare($sql_ingreso);
            $stmt_ingreso->execute([$id_vehiculo_nuevo, $hora_actual, $espacio]);
        }

        $conexion->commit(); 
        
        echo "<script>
                localStorage.setItem('pestanaActiva', 'mensuales');
                alert('¡Cliente, vehículo y cupo asignados con éxito!'); 
                window.location.href = 'index.php';
              </script>";

    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>