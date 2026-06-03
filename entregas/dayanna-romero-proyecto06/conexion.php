<?php
// Credenciales de tu base de datos local
$host = 'localhost';
$dbname = 'control_parqueadero_opt';
$username = 'root';
$password = 'dayanna08'; // Pon tu contraseña aquí si le asignaste una a root, si no, déjalo vacío.

try {
    // Creamos la conexión usando PDO (La forma más segura y recomendada)
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuramos PDO para que nos avise si hay errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Puedes descomentar la siguiente línea solo para probar que funciona
    // echo "¡Conexión exitosa a la base de datos ParkControl!"; 
    
} catch(PDOException $e) {
    // Si algo falla, nos mostrará el error
    die("Error de conexión: " . $e->getMessage());
}
?>