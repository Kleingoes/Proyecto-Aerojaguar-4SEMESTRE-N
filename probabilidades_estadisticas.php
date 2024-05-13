<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Probabilidades y estadísticas - AeroJaguar</title>
    <!-- Agregar Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Probabilidades y estadísticas - AeroJaguar</h1>

    <h2>Probabilidades de usuarios por país</h2>
    <canvas id="chartUsuariosPorPais" width="450" height="170"></canvas>

    <?php
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "aero_jaguar";

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Calcular total de usuarios
    $sql_usuarios_totales = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
    $result_usuarios_totales = $conn->query($sql_usuarios_totales);
    $row_usuarios_totales = $result_usuarios_totales->fetch_assoc();

    // Calcular probabilidad de usuarios por país
    $sql_usuarios_paises = "SELECT Pais, COUNT(*) AS total FROM usuarios GROUP BY Pais";
    $result_usuarios_paises = $conn->query($sql_usuarios_paises);

    // Preparar datos para el gráfico
    $labels = [];
    $data = [];
    $total_usuarios_pais = [];

    if ($result_usuarios_paises->num_rows > 0) {
        while ($row = $result_usuarios_paises->fetch_assoc()) {
            $probabilidad_pais = ($row['total'] / $row_usuarios_totales['total_usuarios']) * 100;
            $labels[] = $row['Pais'] . ' (' . $row['total'] . ' usuarios)';
            $data[] = number_format($probabilidad_pais, 2);
            $total_usuarios_pais[] = $row['total'];
        }
    }

    // Cerrar conexión a la base de datos
    $conn->close();
    ?>

    <script>
        // Obtener el contexto del lienzo para el gráfico
        var ctx = document.getElementById('chartUsuariosPorPais').getContext('2d');

        // Configurar los datos del gráfico
        var data = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Probabilidad de usuarios por país',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Configurar las opciones del gráfico
        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        // Crear el gráfico de barras
        var chartUsuariosPorPais = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    </script>
</body>
</html>
