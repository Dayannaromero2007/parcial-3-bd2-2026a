<?php
require_once 'conexion.php';

if (!isset($_GET['placa']) || !isset($_GET['salida'])) {
    die("Error: Faltan datos para generar el recibo.");
}

$placa = $_GET['placa'];
$salida = $_GET['salida'];

// Buscamos los datos exactos de ese cobro
try {
    $sql = "SELECT v.placa, t.nombre_tipo, i.fecha_hora_ingreso, i.fecha_hora_salida, i.tipo_cliente as regimen, i.Total_pago
            FROM ingresos i
            JOIN vehiculo v ON i.Vehiculo_idVehiculo = v.idVehiculo
            JOIN tipo_vehiculo t ON v.Tipo_vehiculo_idTipo_vehiculo = t.idTipo_vehiculo
            WHERE v.placa = :placa AND i.fecha_hora_salida = :salida";
            
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':placa' => $placa, ':salida' => $salida]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        die("No se encontró la factura en la base de datos.");
    }

    // Calculamos el tiempo
    $fecha1 = new DateTime($ticket['fecha_hora_ingreso']);
    $fecha2 = new DateTime($ticket['fecha_hora_salida']);
    $intervalo = $fecha1->diff($fecha2);
    $duracion = $intervalo->format('%Hh %Im');

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket - <?php echo htmlspecialchars($ticket['placa']); ?></title>
    <style>
        /* Diseño de tirilla de cajero / impresora térmica */
        body {
            font-family: 'Courier New', Courier, monospace; /* Letra de maquinita */
            background-color: #e0e0e0;
            display: flex;
            flex-direction: column; /* Para que el botón quede debajo del ticket */
            align-items: center; /* Centrar todo */
            padding: 20px;
        }
        .ticket-caja {
            background-color: white;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-top: 5px dashed #ccc;
            border-bottom: 5px dashed #ccc;
        }
        .centrado { text-align: center; }
        .titulo { font-size: 22px; font-weight: bold; margin-bottom: 5px; }
        .subtitulo { font-size: 12px; color: #555; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .datos { font-size: 14px; line-height: 1.6; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .fila { display: flex; justify-content: space-between; }
        .total { font-size: 18px; font-weight: bold; margin-top: 10px; text-align: center; }
        .pie { font-size: 11px; text-align: center; color: #777; margin-top: 15px; }

        /* Diseño del botón manual */
        .btn-imprimir {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5a67d8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-imprimir:hover {
            background-color: #4c51bf;
        }
        
        /* MAGIA: Ocultar el botón cuando se imprima en papel */
        @media print {
            .btn-imprimir { 
                display: none; 
            }
        }
    </style>
</head>
<body>

    <div class="ticket-caja">
        <div class="centrado titulo">🚗 ParkControl</div>
        <div class="centrado subtitulo">
            NIT: 900.123.456-7<br>
            Tel: 300 123 4567<br>
            Comprobante de Servicio
        </div>

        <div class="datos">
            <div class="fila"><span>Placa:</span> <strong><?php echo htmlspecialchars($ticket['placa']); ?></strong></div>
            <div class="fila"><span>Tipo:</span> <span><?php echo htmlspecialchars($ticket['nombre_tipo']); ?></span></div>
            <div class="fila"><span>Régimen:</span> <span><?php echo htmlspecialchars($ticket['regimen']); ?></span></div>
            <br>
            <div class="fila"><span>Entrada:</span></div>
            <div class="fila"><span style="font-size: 12px;"><?php echo htmlspecialchars($ticket['fecha_hora_ingreso']); ?></span></div>
            <div class="fila"><span>Salida:</span></div>
            <div class="fila"><span style="font-size: 12px;"><?php echo htmlspecialchars($ticket['fecha_hora_salida']); ?></span></div>
            <div class="fila"><span>Duración:</span> <span><?php echo $duracion; ?></span></div>
        </div>

        <div class="total">
            TOTAL: $ <?php echo number_format($ticket['Total_pago'], 0, ',', '.'); ?>
        </div>

        <div class="pie">
            ¡Gracias por preferirnos!<br>
            El vehículo se entrega en el mismo estado en que ingresó.
        </div>
    </div>

    <button class="btn-imprimir" onclick="window.print()">🖨️ Imprimir Ticket</button>

</body>
</html>