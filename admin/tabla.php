<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Tabla de posiciones';

$sql = "
    SELECT
        e.id,
        e.nombre,
        COUNT(p.id) AS partidos_jugados,

        SUM(
            CASE
                WHEN (
                    p.equipo_local_id = e.id
                    AND p.puntos_local > p.puntos_visitante
                )
                OR (
                    p.equipo_visitante_id = e.id
                    AND p.puntos_visitante > p.puntos_local
                )
                THEN 1
                ELSE 0
            END
        ) AS ganados,

        SUM(
            CASE
                WHEN (
                    p.equipo_local_id = e.id
                    AND p.puntos_local < p.puntos_visitante
                )
                OR (
                    p.equipo_visitante_id = e.id
                    AND p.puntos_visitante < p.puntos_local
                )
                THEN 1
                ELSE 0
            END
        ) AS perdidos,

        SUM(
            CASE
                WHEN p.equipo_local_id = e.id
                THEN p.puntos_local
                ELSE p.puntos_visitante
            END
        ) AS puntos_favor,

        SUM(
            CASE
                WHEN p.equipo_local_id = e.id
                THEN p.puntos_visitante
                ELSE p.puntos_local
            END
        ) AS puntos_contra

    FROM equipos e

    LEFT JOIN partidos p
        ON (
            p.equipo_local_id = e.id
            OR p.equipo_visitante_id = e.id
        )
        AND p.estado = 'finalizado'

    GROUP BY e.id, e.nombre

    ORDER BY
        SUM(
            CASE
                WHEN (
                    p.equipo_local_id = e.id
                    AND p.puntos_local > p.puntos_visitante
                )
                OR (
                    p.equipo_visitante_id = e.id
                    AND p.puntos_visitante > p.puntos_local
                )
                THEN 1
                ELSE 0
            END
        ) DESC,

        (
            SUM(
                CASE
                    WHEN p.equipo_local_id = e.id
                    THEN p.puntos_local
                    ELSE p.puntos_visitante
                END
            )
            -
            SUM(
                CASE
                    WHEN p.equipo_local_id = e.id
                    THEN p.puntos_visitante
                    ELSE p.puntos_local
                END
            )
        ) DESC,

        SUM(
            CASE
                WHEN p.equipo_local_id = e.id
                THEN p.puntos_local
                ELSE p.puntos_visitante
            END
        ) DESC,

        e.nombre ASC
";

$equipos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../src/header.php';
?>

<section class="admin-welcome">
    <div>
        <span class="eyebrow">COMPETICIÓN</span>
        <h1>Tabla de posiciones</h1>
        <p>
            Clasificación de los equipos según los partidos finalizados.
        </p>
    </div>
</section>

<div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; margin:24px 0;">

        <thead>
            <tr>
                <th style="padding:12px; text-align:left;">Pos.</th>
                <th style="padding:12px; text-align:left;">Equipo</th>
                <th style="padding:12px;">PJ</th>
                <th style="padding:12px;">PG</th>
                <th style="padding:12px;">PP</th>
                <th style="padding:12px;">PF</th>
                <th style="padding:12px;">PC</th>
                <th style="padding:12px;">DIF</th>
                <th style="padding:12px;">PTS</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($equipos)): ?>

                <tr>
                    <td colspan="9"
                        style="padding:20px; text-align:center;">
                        Todavía no hay equipos registrados.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($equipos as $i => $equipo): ?>

                    <?php
                    $pj = (int) $equipo['partidos_jugados'];
                    $pg = (int) $equipo['ganados'];
                    $pp = (int) $equipo['perdidos'];
                    $pf = (int) $equipo['puntos_favor'];
                    $pc = (int) $equipo['puntos_contra'];
                    $dif = $pf - $pc;
                    $pts = $pg * 2;
                    ?>

                    <tr>
                        <td style="padding:12px; text-align:left;">
                            <?= $i + 1 ?>
                        </td>

                        <td style="padding:12px; text-align:left;">
                            <?= htmlspecialchars(
                                $equipo['nombre'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pj ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pg ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pp ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pf ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pc ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $dif ?>
                        </td>

                        <td style="padding:12px; text-align:center;">
                            <?= $pts ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>
        </tbody>

    </table>
</div>

<p style="margin-bottom:24px;">
    <strong>Abreviaturas:</strong>
    PJ = partidos jugados,
    PG = partidos ganados,
    PP = partidos perdidos,
    PF = puntos a favor,
    PC = puntos en contra,
    DIF = diferencia de puntos,
    PTS = puntos de clasificación.
</p>

<?php require_once __DIR__ . '/../src/footer.php'; ?>