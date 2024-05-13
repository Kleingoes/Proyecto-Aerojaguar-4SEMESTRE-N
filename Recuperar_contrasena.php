<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "aero_jaguar";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $usuario = $_POST["usuario"];
    $origen_usuario = $_POST["origen_usuario"];
    $nueva_contrasena = $_POST["nueva_contrasena"];

    // Consultar si el usuario y el origen coinciden
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND pais='$origen_usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Encriptar la nueva contraseña
        $contrasena_encriptada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $sql_update = "UPDATE usuarios SET contrasena='$contrasena_encriptada' WHERE usuario='$usuario'";
        if ($conn->query($sql_update) === TRUE) {
            // Contraseña actualizada con éxito
            header("Location: login.html?contrasena_restaurada=exito");
            exit;
        } else {
            echo "Error al restablecer la contraseña: " . $conn->error;
        }
    } else {
        // Usuario o origen incorrecto
        header("Location: recuperar_contrasena.html?error=usuario_origen_invalido");
        exit;
    }
}

$conn->close();
?>
