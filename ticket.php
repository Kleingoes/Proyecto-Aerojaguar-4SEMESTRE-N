<?php
// Iniciar la sesión
session_start();

// Incluir la librería FPDF
require 'FPDF/fpdf.php';

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "aero_jaguar";

$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Función para obtener los detalles de un vuelo
function obtenerDetallesVuelo($idVuelo, $mysqli) {
    $sql = "SELECT * FROM registros_vuelos WHERE id = $idVuelo";
    $resultado = $mysqli->query($sql);

    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false;
    }
}

// Obtener el ID del vuelo
$idVuelo = isset($_GET['id']) ? $mysqli->real_escape_string($_GET['id']) : 1;

// Validar el formato del ID del vuelo
if (filter_var($idVuelo, FILTER_VALIDATE_INT) === false) {
    $idVuelo = 1;
}

// Obtener los detalles del vuelo
$detallesVuelo = obtenerDetallesVuelo($idVuelo, $mysqli);

if (!$detallesVuelo) {
    echo 'No hay datos que coincidan con la consulta';
    exit;
}

// Crear el documento PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Establecer estilos CSS para el ticket
$pdf->SetFillColor(230, 230, 230);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetTextColor(0, 0, 0);

// Agregar información del vuelo
$pdf->Cell(0, 10, 'TICKET DE VUELO', 0, 1, 'C');
$pdf->Ln();
$pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'DETALLES DEL VUELO', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
$pdf->Cell(0, 10, 'Origen: ' . $detallesVuelo['origen'], 0, 1);
$pdf->Cell(0, 10, 'Destino: ' . $detallesVuelo['destino'], 0, 1);
$pdf->Cell(0, 10, 'Fecha: ' . $detallesVuelo['fecha'], 0, 1);
$pdf->Cell(0, 10, 'Hora: ' . $detallesVuelo['hora'], 0, 1);

// Información del usuario
$pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'DATOS DEL USUARIO', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
$pdf->Cell(0, 10, 'Usuario: ' . $_SESSION["usuario"], 0, 1);

// Generar el PDF
$pdf->Output();
?>