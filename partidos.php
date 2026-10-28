
<?php
require_once __DIR__ . '/config/database.php';

function h($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$estado = $_GET['estado'] ?? '';
$estadosValidos = ['programado', 'finalizado', 'suspendido'];

$sql = "SELECT p.fecha_hora, p.estado,
               p.puntos_local, p.puntos_visitante,
               el.nombre AS equipo_local,
               ev.nombre AS equipo_visitante
        FROM partidos p
        INNER JOIN equipos el ON el.id = p.equipo_local_id
        INNER JOIN equipos ev ON ev.id = p.equipo_visitante_id";

$params = [];

if (in_array($estado, $estadosValidos, true)) {
    $sql .= " WHERE p.estado = ?";
    $params[] = $estado;
} else {
    $estado = '';
}

$sql .= " ORDER BY p.fecha_hora DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Partidos | Liga de Básquetbol</title>
<style>
body{margin:0;background:#f3f5f9;color:#17233b;font-family:Arial,sans-serif}
header,footer{background:#101d38;color:white;padding:20px 6%}
header a,footer a{color:white;text-decoration:none;margin-right:14px}
main{max-width:1150px;margin:auto;padding:30px 20px}
.muted{color:#6b778b;font-size:13px}
form{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}
select{padding:11px;border:1px solid #dce2eb;border-radius:7px;background:white}
button{background:#f97316;color:white;border:0;border-radius:7px;padding:11px 16px;cursor:pointer}
.table-wrap{overflow-x:auto;background:white;border:1px solid #e4e9f1;border-radius:10px}
table{border-collapse:collapse;width:100%;min-width:650px}
th,td{text-align:left;padding:13px;border-bottom:1px solid #edf0f5;font-size:13px}
th{background:#e9edf5;color:#4b5870}
.estado{display:inline-block;background:#e8efff;padding:5px 8px;border-radius:20px;font-size:11px}
.finalizado{background:#dff7e8;color:#176b3a}
.suspendido{background:#fee2e2;color:#991b1b}
</style>
</head>
<body>
<header>
    <strong>🏀 LIGA DE BÁSQUETBOL</strong>
    <nav style="margin-top:12px">
        <a href="index.php">Inicio</a>
        <a href="equipos.php">Equipos</a>
        <a href="jugadores.php">Jugadores</a>
        <a href="partidos.php">Partidos</a>
        <a href="posiciones.php">Posiciones</a>
    </nav>
</header>

<main>
    <h1>Partidos y resultados</h1>
    <p class="muted">Calendario y resultados registrados en la competición.</p>

    <form method="get">
        <select name="estado">
            <option value="" <?= $estado === '' ? 'selected' : '' ?>>Todos los partidos</option>
            <option value="programado" <?= $estado === 'programado' ? 'selected' : '' ?>>Programados</option>
            <option value="finalizado" <?= $estado === 'finalizado' ? 'selected' : '' ?>>Finalizados</option>
            <option value="suspendido" <?= $estado === 'suspendido' ? 'selected' : '' ?>>Suspendidos</option>
        </select>
        <button type="submit">Filtrar</button>
        <a href="partidos.php">Limpiar</a>
    </form>

    <p class="muted"><?= count($partidos) ?> partido(s) encontrado(s).</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Equipo local</th>
                    <th>Marcador</th>
                    <th>Equipo visitante</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($partidos as $partido): ?>
                <tr>
                    <td>
                        <?= $partido['fecha_hora']
                            ? h(date('d/m/Y H:i', strtotime($partido['fecha_hora'])))
                            : 'Por confirmar' ?>
                    </td>
                    <td><?= h($partido['equipo_local']) ?></td>
                    <td>
                        <?php if ($partido['estado'] === 'finalizado'): ?>
                            <strong><?= h($partido['puntos_local']) ?> - <?= h($partido['puntos_visitante']) ?></strong>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?= h($partido['equipo_visitante']) ?></td>
                    <td>
                        <span class="estado <?= h($partido['estado']) ?>">
                            <?= h(ucfirst($partido['estado'])) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (!$partidos): ?>
                <tr><td colspan="5">No hay partidos registrados para este filtro.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    <a href="index.php">Volver al inicio</a>
    <a href="admin/login.php">Modo administrador</a>
</footer>
</body>
</html>
