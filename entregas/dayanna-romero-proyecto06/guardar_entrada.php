<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $color = $_POST['color'];
    $tipo = $_POST['tipo_vehiculo'];
    $regimen = $_POST['regimen'];
    
    $espacio_asignado = $_POST['espacio_asignado']; 
    if (empty($espacio_asignado)) {
        $espacio_asignado = null; 
    }
    
    $hora_actual = date('Y-m-d H:i:s'); 
    
    try {
        // ==========================================
        // INICIO DE VALIDACIONES BACKEND (EL CANDADO)
        // ==========================================

        // CANDADO 1: ¿El parqueadero está lleno? (Tope máximo de 100)
        $sql_lleno = "SELECT COUNT(*) as total_ocupados FROM ingresos WHERE fecha_hora_salida IS NULL AND Espacio_idEspacio IS NOT NULL";
        $stmt_lleno = $conexion->query($sql_lleno);
        $resultado_lleno = $stmt_lleno->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado_lleno['total_ocupados'] >= 100) {
            echo "<script>alert('Acceso Denegado: El parqueadero ha alcanzado su límite máximo de 100 vehículos.'); window.location.href = 'index.php';</script>";
            exit(); // Detiene el código, no guarda nada
        }

        // CANDADO 2: ¿El espacio solicitado ya está ocupado por otro carro?
        if ($espacio_asignado !== null) {
            $sql_espacio = "SELECT idIngresos FROM ingresos WHERE Espacio_idEspacio = ? AND fecha_hora_salida IS NULL";
            $stmt_espacio = $conexion->prepare($sql_espacio);
            $stmt_espacio->execute([$espacio_asignado]);
            
            if ($stmt_espacio->fetch()) {
                // Si fetch() encuentra algo, significa que el espacio está ocupado
                echo "<script>alert('Error de asignación: El espacio $espacio_asignado ya se encuentra ocupado. Por favor seleccione otro.'); window.location.href = 'index.php';</script>";
                exit();
            }
        }

        // CANDADO 3 (Bono de seguridad): ¿Este mismo carro (placa) ya está adentro?
        $sql_adentro = "SELECT i.idIngresos FROM ingresos i JOIN vehiculo v ON i.Vehiculo_idVehiculo = v.idVehiculo WHERE v.placa = ? AND i.fecha_hora_salida IS NULL";
        $stmt_adentro = $conexion->prepare($sql_adentro);
        $stmt_adentro->execute([$placa]);
        
        if ($stmt_adentro->fetch()) {
            echo "<script>alert('Error: El vehículo con placa $placa ya registra un ingreso activo en el parqueadero.'); window.location.href = 'index.php';</script>";
            exit();
        }

        // ==========================================
        // FIN DE VALIDACIONES. SI PASA LOS CANDADOS, SE GUARDA.
        // ==========================================

        // 1. Guardamos el vehículo (Usamos INSERT IGNORE por si ya nos había visitado antes)
        $sql_vehiculo = "INSERT IGNORE INTO vehiculo (placa, marca, color, Tipo_vehiculo_idTipo_vehiculo) VALUES (?, ?, ?, ?)";
        $stmt_vehiculo = $conexion->prepare($sql_vehiculo);
        $stmt_vehiculo->execute([$placa, $marca, $color, $tipo]);
        
        // 2. Buscamos el ID numérico que la base de datos le dio al vehículo
        $sql_buscar_id = "SELECT idVehiculo FROM vehiculo WHERE placa = ?";
        $stmt_buscar = $conexion->prepare($sql_buscar_id);
        $stmt_buscar->execute([$placa]);
        $vehiculo = $stmt_buscar->fetch(PDO::FETCH_ASSOC);
        $id_del_vehiculo = $vehiculo['idVehiculo'];
        
        // 3. Registramos el ingreso oficial 
        $sql_ingreso = "INSERT INTO ingresos (Vehiculo_idVehiculo, fecha_hora_ingreso, tipo_cliente, Espacio_idEspacio) VALUES (?, ?, ?, ?)";
        $stmt_ingreso = $conexion->prepare($sql_ingreso);
        $stmt_ingreso->execute([$id_del_vehiculo, $hora_actual, $regimen, $espacio_asignado]);
        
        echo "<script>alert('¡Vehículo registrado con éxito!'); window.location.href = 'index.php';</script>";
        exit();
        
    } catch(PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
}