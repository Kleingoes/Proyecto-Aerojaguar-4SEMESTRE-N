<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aero_jaguar";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit;
}

// Obtener el ID del usuario
$usuario = $_SESSION["usuario"];
$sql = "SELECT id FROM usuarios WHERE usuario='$usuario'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id_usuario = $row["id"];
} else {
    echo "Error: No se encontró el usuario";
    exit;
}

// Recibir datos del formulario
$origen = $_POST['origen'];
$destino = $_POST['destino'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$plazas_disponibles = $_POST['plazas_disponibles'];

// Preparar y ejecutar la consulta SQL para insertar el vuelo
$sql = "INSERT INTO registros_vuelos (id_usuario, origen, destino, fecha, hora, plazas_disponibles)
        VALUES ('$id_usuario', '$origen', '$destino', '$fecha', '$hora', '$plazas_disponibles')";

if ($conn->query($sql) === TRUE) {
    echo "Vuelo registrado exitosamente";
    // Redireccionar al usuario al ticket generado
    header("Location: ticket.php?id=" . $conn->insert_id);
} else {
    echo "Error al registrar el vuelo: " . $conn->error;
}

$conn->close();
?>
