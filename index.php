<?php
require_once __DIR__ . '/config/database.php';

$totalEquipos = $pdo->query(
    "SELECT COUNT(*) FROM equipos"
)->fetchColumn();

$totalJugadores = $pdo->query(
    "SELECT COUNT(*) FROM jugadores"
)->fetchColumn();

$totalPartidos = $pdo->query(
    "SELECT COUNT(*) FROM partidos"
)->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Liga de Básquetbol</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #172033;
        }

        header {
            background: #172554;
            color: white;
            padding: 28px 20px;
        }

        header h1 {
            margin: 0 0 8px;
        }

        main {
            max-width: 1050px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .estado {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .tarjetas {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
        }

        .tarjeta {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0000000a;
        }

        .tarjeta h2 {
            font-size: 18px;
        }

        .numero {
            color: #ea580c;
            font-size: 34px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>🏀 Liga de Básquetbol</h1>
    <p>Panel de administración de la competición</p>
</header>

<main>
    <div class="estado">
        ✓ Conexión con la base de datos establecida.
    </div>

    <h2>Panel principal</h2>

    <section class="tarjetas">
        <article class="tarjeta">
            <h2>Equipos</h2>
            <p class="numero"><?= $totalEquipos ?></p>
            <p>Equipos registrados</p>
        </article>

        <article class="tarjeta">
            <h2>Jugadores</h2>
            <p class="numero"><?= $totalJugadores ?></p>
            <p>Jugadores registrados</p>
        </article>

        <article class="tarjeta">
            <h2>Partidos</h2>
            <p class="numero"><?= $totalPartidos ?></p>
            <p>Partidos registrados</p>
        </article>
    </section>
</main>

</body>
</html>
