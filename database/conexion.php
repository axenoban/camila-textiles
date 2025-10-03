<?php
// conexion.php

$host = 'localhost';       // Dirección del servidor de base de datos
$dbname = 'camila_textil'; // Nombre de la base de datos
$username = 'root';        // Usuario de la base de datos (ajustar según tu configuración)
$password = '';            // Contraseña de la base de datos (ajustar según tu configuración)

try {
    // Estableciendo la conexión utilizando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Configurando el manejo de errores de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");  // Aseguramos que la codificación sea UTF-8
    // echo "Conexión exitosa a la base de datos";  // Descomentar para verificar conexión

} catch (PDOException $e) {
    // En caso de error, mostramos el mensaje de error
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit();
}
?>
