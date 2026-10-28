
<?php
require_once __DIR__ . '/config/database.php';

/**
 * Escapa los valores para mostrarlos de forma segura en HTML.
 */
function h($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/*
 * Calcula la clasificación usando exclusivamente partidos finalizados.
 * Los equipos sin partidos también aparecen en la tabla.
 */
$sql = "
    SELECT
        tabla.*,
        (tabla.puntos_favor - tabla.puntos_contra) AS diferencia
    FROM (
        SELECT
            e.id,
            e.nombre,

            COUNT(p.id) AS jugados,

            COALESCE(SUM(
                CASE
                    WHEN p.id IS NOT NULL AND (
                        (p.equipo_local_id = e.id
                            AND p.puntos_local > p.puntos_visitante)
                        OR
                        (p.equipo_visitante_id = e.id
                            AND p.puntos_visitante > p.puntos_local)
                    )
                    THEN 1 ELSE 0
                END
            ), 0) AS ganados,

            COALESCE(SUM(
                CASE
                    WHEN p.id IS NOT NULL AND (
                        (p.equipo_local_id = e.id
                            AND p.puntos_local < p.puntos_visitante)
                        OR
                        (p.equipo_visitante_id = e.id
                            AND p.puntos_visitante < p.puntos_local)
                    )
                    THEN 1 ELSE 0
                END
            ), 0) AS perdidos,

            COALESCE(SUM(
                CASE
                    WHEN p.equipo_local_id = e.id
                        THEN p.puntos_local
                    WHEN p.equipo_visitante_id = e.id
                        THEN p.puntos_visitante
                    ELSE 0
                END
            ), 0) AS puntos_favor,

            COALESCE(SUM(
                CASE
                    WHEN p.equipo_local_id = e.id
                        THEN p.puntos_visitante
                    WHEN p.equipo_visitante_id = e.id
                        THEN p.puntos_local
                    ELSE 0
                END
            ), 0) AS puntos_contra

        FROM equipos e

        LEFT JOIN partidos p
            ON (
                p.equipo_local_id = e.id
                OR p.equipo_visitante_id = e.id
            )
            AND p.estado = 'finalizado'

        GROUP BY e.id, e.nombre
    ) AS tabla

    ORDER BY
        tabla.ganados DESC,
        diferencia DESC,
        tabla.puntos_favor DESC,
        tabla.nombre ASC
";

$stmt = $pdo->query($sql);
$posiciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tabla = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tabla de posiciones | Liga de Básquetbol</title>

    <style>
        :root {
            --azul: #101d38;
            --azul-claro: #1d3156;
            --naranja: #f97316;
            --fondo: #f3f5f9;
            --texto: #17233b;
            --gris: #6b778b;
            --blanco: #ffffff;
            --borde: #e4e9f1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--fondo);
            color: var(--texto);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        header {
            background: var(--azul);
            color: white;
            padding: 16px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .marca {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            background: var(--naranja);
            border-radius: 11px;
            font-size: 23px;
        }

        .marca strong {
            display: block;
            font-size: 14px;
        }

        .marca small {
            color: #bdc9dc;
            font-size: 11px;
        }

        nav {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        nav a {
            color: #e0e7f1;
            font-size: 12px;
            font-weight: 600;
        }

        nav a:hover {
            color: #fb923c;
        }

        .admin {
            border: 1px solid #687792;
            border-radius: 8px;
            padding: 8px 11px;
            color: white;
            font-size: 12px;
        }

        .portada {
            padding: 38px 6%;
            color: white;
            background:
                linear-gradient(110deg, #101d38, #263f68);
        }

        .portada small {
            color: #fdba74;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .portada h1 {
            font-size: clamp(28px, 4vw, 40px);
            line-height: 1.2;
            margin: 8px 0;
        }

        .portada p {
            max-width: 720px;
            color: #d5deec;
            font-size: 14px;
        }

        main {
            max-width: 1150px;
            margin: auto;
            padding: 30px 22px 45px;
        }

        .resumen {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 25px;
        }

        .tarjeta {
            background: white;
            border: 1px solid var(--borde);
            border-radius: 12px;
            padding: 20px;
        }

        .tarjeta span {
            color: var(--gris);
            font-size: 12px;
        }

        .tarjeta strong {
            display: block;
            font-size: 29px;
            color: var(--azul);
        }

        .tabla-contenedor {
            overflow-x: auto;
            background: white;
            border: 1px solid var(--borde);
            border-radius: 12px;
        }

        table {
            width: 100%;
            min-width: 680px;
            border-collapse: collapse;
        }

        thead {
            background: #e9edf5;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf0f5;
            text-align: center;
            font-size: 13px;
        }

        th {
            color: #4b5870;
            font-size: 11px;
            text-transform: uppercase;
        }

        th:nth-child(2), td:nth-child(2) {
            text-align: left;
        }

        tbody tr:first-child {
            background: #fff8ed;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .puesto {
            color: #ea580c;
            font-weight: 800;
        }

        .nota {
            color: var(--gris);
            font-size: 12px;
            margin-top: 15px;
        }

        .vacio {
            background: white;
            border: 1px solid var(--borde);
            border-radius: 10px;
            padding: 22px;
            color: var(--gris);
        }

        footer {
            background: var(--azul);
            color: #c4cede;
            padding: 22px 6%;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 12px;
        }

        footer a {
            color: white;
        }

        @media (max-width: 750px) {
            header {
                padding: 14px 5%;
            }

            nav {
                width: 100%;
            }

            .resumen {
                grid-template-columns: 1fr;
            }

            main {
                padding: 22px 14px 35px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="index.php" class="marca">
        <span class="logo">🏀</span>
        <span>
            <strong>LIGA DE BÁSQUETBOL</strong>
            <small>Portal oficial de competición</small>
        </span>
    </a>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="equipos.php">Equipos</a>
        <a href="jugadores.php">Jugadores</a>
        <a href="partidos.php">Partidos</a>
        <a href="posiciones.php">Posiciones</a>
    </nav>

    <a class="admin" href="admin/login.php">🔐 Administrador</a>
</header>

<section class="portada">
    <small>CLASIFICACIÓN OFICIAL</small>
    <h1>🏆 Tabla de posiciones</h1>
    <p>
        Consulta el rendimiento de los equipos según sus partidos
        finalizados, victorias, derrotas y puntos anotados.
    </p>
</section>

<main>
    <section class="resumen">
        <article class="tarjeta">
            <span>Equipos participantes</span>
            <strong><?= count($tabla) ?></strong>
        </article>

        <article class="tarjeta">
            <span>Partidos finalizados</span>
            <strong>
                <?php
                $totalJugados = 0;
                foreach ($tabla as $equipo) {
                    $totalJugados += (int) $equipo['jugados'];
                }
                echo (int) ($totalJugados / 2);
                ?>
            </strong>
        </article>

        <article class="tarjeta">
            <span>Equipos con victorias</span>
            <strong>
                <?php
                $conVictorias = 0;
                foreach ($tabla as $equipo) {
                    if ((int) $equipo['ganados'] > 0) {
                        $conVictorias++;
                    }
                }
                echo $conVictorias;
                ?>
            </strong>
        </article>
    </section>

    <h2>Clasificación de equipos</h2>

    <?php if ($tabla): ?>
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Equipo</th>
                        <th>PJ</th>
                        <th>PG</th>
                        <th>PP</th>
                        <th>PF</th>
                        <th>PC</th>
                        <th>Dif.</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tabla as $indice => $equipo): ?>
                        <?php
                        $favor = (int) $equipo['puntos_favor'];
                        $contra = (int) $equipo['puntos_contra'];
                        ?>
                        <tr>
                            <td class="puesto">
                                <?= $indice + 1 ?>
                            </td>

                            <td>
                                <strong><?= h($equipo['nombre']) ?></strong>
                            </td>

                            <td><?= (int) $equipo['jugados'] ?></td>
                            <td><?= (int) $equipo['ganados'] ?></td>
                            <td><?= (int) $equipo['perdidos'] ?></td>
                            <td><?= $favor ?></td>
                            <td><?= $contra ?></td>
                            <td><?= $favor - $contra ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="nota">
            PJ: partidos jugados · PG: partidos ganados ·
            PP: partidos perdidos · PF: puntos a favor ·
            PC: puntos en contra · Dif.: diferencia de puntos.
        </p>
    <?php else: ?>
        <div class="vacio">
            No hay equipos registrados en la base de datos.
        </div>
    <?php endif; ?>

</main>

<footer>
    <span>🏀 Liga de Básquetbol · <?= date('Y') ?></span>
    <a href="index.php">← Volver al inicio</a>
</footer>

</body>
</html>
