<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];
    $hora_salida = date('Y-m-d H:i:s');

    try {
        // 1. Extraemos el ingreso activo y los precios específicos para ese tipo de vehículo
        $sql = "SELECT i.idIngresos, i.fecha_hora_ingreso,
                       t.precio_hora, t.precio_dia
                FROM ingresos i
                JOIN vehiculo v ON i.Vehiculo_idVehiculo = v.idVehiculo
                JOIN tarifa t ON v.Tipo_vehiculo_idTipo_vehiculo = t.Tipo_vehiculo_idTipo_vehiculo
                WHERE v.placa = ? AND i.fecha_hora_salida IS NULL";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$placa]);
        $ingreso = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ingreso) {
            
            // --- EXPLICACIÓN DE VARIABLES MATEMÁTICAS ---
            // $entrada: Convierte el texto de la fecha de ingreso a formato de tiempo matemático (segundos).
            // $salida: Convierte la hora actual a segundos.
            // $minutos: Resta la salida menos la entrada para sacar el tiempo total, y lo divide en 60.
            
            $entrada = strtotime($ingreso['fecha_hora_ingreso']);
            $salida = strtotime($hora_salida);
            $minutos = round(($salida - $entrada) / 60);
            
            // --- LÓGICA DE COBRO (FRACCIÓN O HORA COMPLETA) ---
            // La función ceil() redondea hacia arriba. Ej: Si estuvo 61 minutos (1h y 1m), ceil cobrará 2 horas.
            // Si el tiempo es menor a 1 hora, se cobra 1 hora como tarifa mínima.
            
            if ($minutos <= 60) {
                $horas_cobrar = 1;
            } else {
                $horas_cobrar = ceil($minutos / 60);
            }

            // Calculamos el total base multiplicando las horas por el precio_hora
            $total_a_pagar = $horas_cobrar * $ingreso['precio_hora'];

            // --- LÓGICA DE TOPE MÁXIMO (PRECIO DÍA) ---
            // Si un carro se queda 15 horas, el cobro por hora sería abusivo. 
            // Evaluamos si el cálculo superó la tarifa diaria. Si es así, limitamos el cobro al valor del día.
            if ($total_a_pagar > $ingreso['precio_dia']) {
                $total_a_pagar = $ingreso['precio_dia'];
            }

            // 4. Actualizamos la fila en la BD con la fecha de salida y el dinero a cobrar
            $update = "UPDATE ingresos SET fecha_hora_salida = ?, total_pago = ? WHERE idIngresos = ?";
            $conexion->prepare($update)->execute([$hora_salida, $total_a_pagar, $ingreso['idIngresos']]);

            // Formateamos el número para que se vea con puntos de miles (ej: 3.000)
            $total_formateado = number_format($total_a_pagar, 0, ',', '.');

            echo "<script>alert('Salida Exitosa.\\n\\nTiempo en parqueadero: $minutos min.\\nTotal a pagar: $$total_formateado'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error: No se encontró la entrada activa del vehículo.'); window.location.href = 'index.php';</script>";
        }
    } catch(PDOException $e) {
        die("Error en base de datos: " . $e->getMessage());
    }
}
?>
?>