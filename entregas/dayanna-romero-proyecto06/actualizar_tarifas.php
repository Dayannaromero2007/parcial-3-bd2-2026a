<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capturamos el ID
    $id = $_POST['id_tipo'];
    
    // 2. Preparamos los datos: si el campo está vacío, enviamos 0
    $ph = !empty($_POST["precio_hora_$id"]) ? $_POST["precio_hora_$id"] : 0;
    $pd = !empty($_POST["precio_dia_$id"]) ? $_POST["precio_dia_$id"] : 0;
    $pn = !empty($_POST["precio_noche_$id"]) ? $_POST["precio_noche_$id"] : 0;
    $pfs = !empty($_POST["precio_fin_semana_$id"]) ? $_POST["precio_fin_semana_$id"] : 0;
    $php = !empty($_POST["precio_hora_pico_$id"]) ? $_POST["precio_hora_pico_$id"] : 0;
    $pf = !empty($_POST["precio_dias_festivos_$id"]) ? $_POST["precio_dias_festivos_$id"] : 0;
    $pm = !empty($_POST["precio_mensual_$id"]) ? $_POST["precio_mensual_$id"] : 0;

    // 3. Ejecutamos la actualización
    $sql = "UPDATE tarifa SET 
            precio_hora = :ph, precio_dia = :pd, precio_noche = :pn, 
            precio_fin_semana = :pfs, precio_hora_pico = :php, 
            precio_festivos = :pf, precio_mensual = :pm 
            WHERE Tipo_vehiculo_idTipo_vehiculo = :id";

    $stmt = $conexion->prepare($sql);
    
    try {
        $stmt->execute([
            ':ph'  => $ph,
            ':pd'  => $pd,
            ':pn'  => $pn,
            ':pfs' => $pfs,
            ':php' => $php,
            ':pf'  => $pf,
            ':pm'  => $pm,
            ':id'  => $id
        ]);
        echo "<script>alert('¡Tarifas actualizadas!'); window.location.href = 'index.php';</script>";
    } catch(PDOException $e) {
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>