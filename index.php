<?php
require_once __DIR__ . '/config/database.php';

$totalEquipos = (int) $pdo->query(
    "SELECT COUNT(*) FROM equipos"
)->fetchColumn();

$totalJugadores = (int) $pdo->query(
    "SELECT COUNT(*) FROM jugadores"
)->fetchColumn();

$totalPartidos = (int) $pdo->query(
    "SELECT COUNT(*) FROM partidos"
)->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Liga de Básquetbol | Portal oficial</title>

    <style>
        :root {
            --azul: #101d38;
            --azul-claro: #1d3156;
            --naranja: #f97316;
            --fondo: #f4f6fa;
            --texto: #19243a;
            --gris: #6b7280;
            --blanco: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--fondo);
            color: var(--texto);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        /* BARRA SUPERIOR */
        .barra {
            background: var(--azul);
            color: white;
            padding: 0 7%;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .marca {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
        }

        .logo {
            width: 45px;
            height: 45px;
            background: var(--naranja);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .marca h1 {
            font-size: 19px;
            line-height: 1.3;
        }

        .marca p {
            font-size: 12px;
            color: #b9c6dc;
        }

        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: white;
            border: 1px solid #65738c;
            border-radius: 9px;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s;
        }

        .admin-link:hover {
            background: var(--naranja);
            border-color: var(--naranja);
        }

        /* PORTADA */
        .portada {
            background:
                radial-gradient(
                    circle at 85% 25%,
                    #334b75 0,
                    transparent 35%
                ),
                linear-gradient(120deg, #101d38, #1d3156);
            color: white;
            padding: 75px 7% 85px;
        }

        .portada-contenido {
            max-width: 1100px;
            margin: auto;
        }

        .etiqueta {
            display: inline-block;
            color: #fed7aa;
            background: #f9731625;
            border: 1px solid #fb923c55;
            border-radius: 30px;
            padding: 6px 13px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .portada h2 {
            max-width: 720px;
            font-size: clamp(34px, 5vw, 55px);
            line-height: 1.13;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }

        .portada h2 span {
            color: #fb923c;
        }

        .portada p {
            max-width: 620px;
            color: #d1d9e8;
            font-size: 16px;
            margin-bottom: 28px;
        }

        .boton-principal {
            display: inline-block;
            background: var(--naranja);
            color: white;
            padding: 13px 20px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.2s;
        }

        .boton-principal:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }

        /* CONTENIDO */
        main {
            max-width: 1150px;
            margin: -35px auto 0;
            padding: 0 25px;
            position: relative;
        }

        .seccion-titulo {
            margin: 30px 0 18px;
        }

        .seccion-titulo h2 {
            font-size: 23px;
            letter-spacing: -0.4px;
        }

        .seccion-titulo p {
            color: var(--gris);
            font-size: 14px;
            margin-top: 4px;
        }

        /* TARJETAS */
        .estadisticas {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .tarjeta {
            background: var(--blanco);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #e7eaf0;
            box-shadow: 0 8px 24px #17203308;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .tarjeta:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px #17203312;
        }

        .tarjeta-superior {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .icono-tarjeta {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .icono-naranja {
            background: #fff0e5;
        }

        .icono-azul {
            background: #e9efff;
        }

        .icono-verde {
            background: #e5f8ed;
        }

        .etiqueta-dato {
            color: #64748b;
            font-size: 11px;
            background: #f1f5f9;
            border-radius: 20px;
            padding: 5px 9px;
        }

        .tarjeta h3 {
            color: #596579;
            font-size: 14px;
            font-weight: 600;
        }

        .numero {
            font-size: 38px;
            font-weight: 750;
            line-height: 1.4;
            color: var(--azul);
        }

        .tarjeta small {
            color: #8a94a5;
            font-size: 12px;
        }

        /* BLOQUE INFORMATIVO */
        .bloque {
            margin: 28px 0 45px;
            background: white;
            border: 1px solid #e7eaf0;
            border-radius: 15px;
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .bloque h2 {
            font-size: 19px;
            margin-bottom: 7px;
        }

        .bloque p {
            color: var(--gris);
            font-size: 14px;
            max-width: 650px;
        }

        .bloque-icono {
            font-size: 44px;
            flex-shrink: 0;
        }

        /* PIE DE PÁGINA */
        footer {
            background: var(--azul);
            color: #c3cddd;
            padding: 25px 7%;
            text-align: center;
            font-size: 12px;
        }

        footer strong {
            color: white;
        }

        @media (max-width: 700px) {
            .barra {
                padding: 12px 5%;
            }

            .portada {
                padding: 55px 6% 70px;
            }

            main {
                padding: 0 16px;
            }

            .estadisticas {
                grid-template-columns: 1fr;
            }

            .tarjeta {
                padding: 21px;
            }

            .bloque {
                align-items: flex-start;
                padding: 22px;
            }

            .bloque-icono {
                display: none;
            }
        }
    </style>
</head>

<body>

    <header class="barra">
        <div class="marca">
            <div class="logo">🏀</div>
            <div>
                <h1>LIGA DE BÁSQUETBOL</h1>
                <p>Portal oficial de competición</p>
            </div>
        </div>

        <!-- ACCESO AL ADMINISTRADOR EN LA PARTE SUPERIOR -->
        <a class="admin-link" href="admin/index.php">
            <span>🔐</span>
            Modo administrador
            <span>↗</span>
        </a>
    </header>

    <section class="portada">
        <div class="portada-contenido">
            <span class="etiqueta">TEMPORADA DEPORTIVA</span>

            <h2>
                La pasión por el básquetbol,
                <span>en un solo lugar.</span>
            </h2>

            <p>
                Consulta la información de la liga, conoce a los equipos
                participantes y mantente al día con las estadísticas de
                la competición.
            </p>

            <a href="#resumen" class="boton-principal">
                Explorar la liga &nbsp; ↓
            </a>
        </div>
    </section>

    <main id="resumen">

        <div class="seccion-titulo">
            <h2>Resumen de la competición</h2>
            <p>Información general registrada en el sistema.</p>
        </div>

        <section class="estadisticas">

            <article class="tarjeta">
                <div class="tarjeta-superior">
                    <div class="icono-tarjeta icono-naranja">🏆</div>
                    <span class="etiqueta-dato">REGISTRO</span>
                </div>

                <h3>Equipos inscritos</h3>
                <div class="numero"><?= $totalEquipos ?></div>
                <small>Equipos registrados en la liga</small>
            </article>

            <article class="tarjeta">
                <div class="tarjeta-superior">
                    <div class="icono-tarjeta icono-azul">👥</div>
                    <span class="etiqueta-dato">PLANTILLAS</span>
                </div>

                <h3>Jugadores registrados</h3>
                <div class="numero"><?= $totalJugadores ?></div>
                <small>Jugadores inscritos en los equipos</small>
            </article>

            <article class="tarjeta">
                <div class="tarjeta-superior">
                    <div class="icono-tarjeta icono-verde">📊</div>
                    <span class="etiqueta-dato">COMPETICIÓN</span>
                </div>

                <h3>Partidos programados o registrados</h3>
                <div class="numero"><?= $totalPartidos ?></div>
                <small>Partidos almacenados en el sistema</small>
            </article>

        </section>

        <section class="bloque">
            <div>
                <h2>Todo el campeonato, organizado en un solo sistema</h2>
                <p>
                    La plataforma centraliza los datos de los equipos,
                    jugadores y partidos. El administrador gestiona la
                    información para mantener actualizado el campeonato.
                </p>
            </div>

            <div class="bloque-icono">🏀</div>
        </section>

    </main>

    <footer>
        <strong>Liga de Básquetbol</strong>
        <p>Sistema de gestión deportiva · <?= date('Y') ?></p>
    </footer>

</body>
</html>