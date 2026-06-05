<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tipo'])) {
    
    // 1. Capturamos el ID del vehículo (1, 2, 3, 4 o 5)
    $id = $_POST['id_tipo'];
    
    // 2. Preparamos los datos: quitamos los puntos de los miles y si está vacío enviamos 0
    // Usamos str_replace para cambiar el punto '.' por nada ''
    $ph = !empty($_POST["precio_hora_$id"]) ? str_replace('.', '', $_POST["precio_hora_$id"]) : 0;
    $pd = !empty($_POST["precio_dia_$id"]) ? str_replace('.', '', $_POST["precio_dia_$id"]) : 0;
    $pn = !empty($_POST["precio_noche_$id"]) ? str_replace('.', '', $_POST["precio_noche_$id"]) : 0;
    $pfs = !empty($_POST["precio_fin_semana_$id"]) ? str_replace('.', '', $_POST["precio_fin_semana_$id"]) : 0;
    $php = !empty($_POST["precio_hora_pico_$id"]) ? str_replace('.', '', $_POST["precio_hora_pico_$id"]) : 0;
    $pf = !empty($_POST["precio_dias_festivos_$id"]) ? str_replace('.', '', $_POST["precio_dias_festivos_$id"]) : 0;
    $pm = !empty($_POST["precio_mensual_$id"]) ? str_replace('.', '', $_POST["precio_mensual_$id"]) : 0;

    // 3. Ejecutamos la actualización
    $sql = "UPDATE tarifa SET 
            precio_hora = :ph, 
            precio_dia = :pd, 
            precio_noche = :pn, 
            precio_fin_semana = :pfs, 
            precio_hora_pico = :php, 
            precio_festivos = :pf, 
            precio_mensual = :pm 
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
        
        // 4. Mostramos la alerta y usamos la memoria del navegador para volver a la pestaña Tarifas
        echo "<script>
                localStorage.setItem('pestanaActiva', 'tarifas');
                alert('¡Tarifas del vehículo actualizadas correctamente!'); 
                window.location.href = 'index.php';
              </script>";
              
    } catch(PDOException $e) {
        echo "Error al guardar en la base de datos: " . $e->getMessage();
    }
} else {
    // Si alguien intenta entrar directo al archivo sin enviar formulario, lo devolvemos
    header("Location: index.php");
    exit;
}
?>