
<?php
$titulo = "Gestión de Liga de Básquetbol";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f3f4f6;
            color: #1f2937;
        }

        header {
            background: #172554;
            color: white;
            padding: 24px;
            text-align: center;
        }

        main {
            max-width: 900px;
            margin: 35px auto;
            padding: 24px;
            background: white;
            border-radius: 10px;
        }

        .modulos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .modulo {
            padding: 20px;
            background: #eff6ff;
            border-radius: 8px;
            text-align: center;
        }

        .estado {
            color: #166534;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1><?php echo $titulo; ?></h1>
    <p>Administración de equipos, jugadores y partidos</p>
</header>

<main>
    <h2>Panel principal</h2>

    <p>Bienvenido al sistema de gestión de la liga.</p>

    <p class="estado">
        Aplicación PHP ejecutándose correctamente.
    </p>

    <div class="modulos">
        <div class="modulo">
            <h3>Equipos</h3>
            <p>Gestión de equipos participantes.</p>
        </div>

        <div class="modulo">
            <h3>Jugadores</h3>
            <p>Registro de jugadores por equipo.</p>
        </div>

        <div class="modulo">
            <h3>Partidos</h3>
            <p>Programación y resultados.</p>
        </div>

        <div class="modulo">
            <h3>Estadísticas</h3>
            <p>Resultados y posiciones de la liga.</p>
        </div>
    </div>
</main>

</body>
</html>
