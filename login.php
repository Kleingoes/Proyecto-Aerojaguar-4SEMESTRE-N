
<?php
// Iniciar la sesión
session_start();

// Variable para el mensaje de éxito
$mensaje = "";

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "aero_jaguar";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Registro o inicio de sesión de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nuevo_usuario']) && isset($_POST['nueva_contrasena']) && isset($_POST['pais'])) {
        // Registro de nuevo usuario
        $usuario = $_POST["nuevo_usuario"];
        $contrasena = $_POST["nueva_contrasena"];
        $pais = $_POST["pais"];

        // Encriptar la contraseña antes de guardarla en la base de datos
        $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

        // Verificar si el usuario que se está registrando es el administrador
        if ($usuario === 'administrador') {
            // Insertar el usuario administrador en la base de datos
            $sql_insert_admin = "INSERT INTO usuarios (usuario, contrasena, pais) VALUES ('$usuario', '$contrasena_encriptada', '$pais')";
            if ($conn->query($sql_insert_admin) === TRUE) {
                // Establece el mensaje de éxito
                $mensaje = "¡Registro exitoso!";
                header("Location: login.html?registro=exito");
                exit;
            } else {
                echo "Error al registrar el usuario: " . $conn->error;
            }
        } else {
            // Consulta SQL para verificar si el usuario ya existe en la base de datos
            $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                header("Location: login.html?registro=error");
                exit;
            } else {
                $sql_insert = "INSERT INTO usuarios (usuario, contrasena, pais) VALUES ('$usuario', '$contrasena_encriptada', '$pais')";
                if ($conn->query($sql_insert) === TRUE) {
                    // Establece el mensaje de éxito
                    $mensaje = "¡Registro exitoso!";
                    header("Location: login.html?registro=exito");
                } else {
                    echo "Error al registrar el usuario: " . $conn->error;
                }
            }
        }
    } elseif (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
        // Inicio de sesión
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];

        // Consulta SQL 
        $sql = "SELECT id, contrasena FROM usuarios WHERE usuario='$usuario'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Obtener la contraseña encriptada de la base de datos
            $row = $result->fetch_assoc();
            $contrasena_encriptada = $row["contrasena"];
            
            // Verifica si la contraseña ingresada coincide con la contraseña de la base de datos
            if (password_verify($contrasena, $contrasena_encriptada)) {
                // Las credenciales son correctas
                $_SESSION["usuario"] = $usuario;
                $_SESSION["usuario_id"] = $row["id"]; // Almacenar el ID del usuario en la sesión
                
                // Verificar si el usuario es el administrador
                if ($usuario === 'administrador') {
                    // Redirigir al administrador a la página de probabilidades y estadísticas
                    header("Location: probabilidades_estadisticas.php");
                    exit;
                } else {
                    // Redirecciona a una página de inicio de sesión exitosa
                    header("Location: acciones.html?inicio=exitoso");
                    exit;
                }
            } else {
                // Si la contraseña es incorrecta
                header("Location: login.html?inicio=error");
                exit;
            }
        } else {
            // Si el usuario no existe
            header("Location: login.html?inicio=error");
            exit;
        }
    }
}

$conn->close();
?>
