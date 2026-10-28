
<?php
require_once __DIR__ . '/config/database.php';

function h($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$busqueda = trim($_GET['q'] ?? '');
$equipoId = (int) ($_GET['equipo_id'] ?? 0);

$equipos = $pdo->query(
    "SELECT id, nombre FROM equipos ORDER BY nombre"
)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT j.id, j.nombres, j.apellidos, j.dorsal,
               j.posicion, e.nombre AS equipo, e.id AS equipo_id
        FROM jugadores j
        INNER JOIN equipos e ON e.id = j.equipo_id
        WHERE 1=1";

$params = [];

if ($busqueda !== '') {
    $sql .= " AND (j.nombres LIKE ?
                   OR j.apellidos LIKE ?
                   OR e.nombre LIKE ?
                   OR j.posicion LIKE ?)";
    $filtro = "%{$busqueda}%";
    array_push($params, $filtro, $filtro, $filtro, $filtro);
}

if ($equipoId > 0) {
    $sql .= " AND e.id = ?";
    $params[] = $equipoId;
}

$sql .= " ORDER BY e.nombre, j.apellidos, j.nombres";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Jugadores | Liga de Básquetbol</title>
<style>
body{margin:0;background:#f3f5f9;color:#17233b;font-family:Arial,sans-serif}
header,footer{background:#101d38;color:white;padding:20px 6%}
header a,footer a{color:white;text-decoration:none;margin-right:14px}
main{max-width:1150px;margin:auto;padding:30px 20px}
.muted{color:#6b778b;font-size:13px}
form{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}
input,select{padding:11px;border:1px solid #dce2eb;border-radius:7px;background:white}
input{flex:1;min-width:200px}
button{background:#f97316;color:white;border:0;border-radius:7px;padding:11px 16px;cursor:pointer}
.table-wrap{overflow-x:auto;background:white;border:1px solid #e4e9f1;border-radius:10px}
table{border-collapse:collapse;width:100%;min-width:600px}
th,td{text-align:left;padding:13px;border-bottom:1px solid #edf0f5;font-size:13px}
th{background:#e9edf5;color:#4b5870}
tbody tr:hover{background:#fafbfe}
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
    <h1>Jugadores registrados</h1>
    <p class="muted">Consulta las plantillas de todos los equipos.</p>

    <form method="get">
        <input type="search" name="q"
               value="<?= h($busqueda) ?>"
               placeholder="Buscar jugador, posición o equipo">

        <select name="equipo_id">
            <option value="0">Todos los equipos</option>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?= (int) $equipo['id'] ?>"
                    <?= $equipoId === (int) $equipo['id'] ? 'selected' : '' ?>>
                    <?= h($equipo['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
        <a href="jugadores.php">Limpiar</a>
    </form>

    <p class="muted"><?= count($jugadores) ?> jugador(es) encontrado(s).</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Dorsal</th>
                    <th>Jugador</th>
                    <th>Equipo</th>
                    <th>Posición</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($jugadores as $jugador): ?>
                <tr>
                    <td>#<?= h($jugador['dorsal'] ?? '—') ?></td>
                    <td><?= h($jugador['nombres'] . ' ' . $jugador['apellidos']) ?></td>
                    <td><?= h($jugador['equipo']) ?></td>
                    <td><?= h($jugador['posicion'] ?: 'No registrada') ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (!$jugadores): ?>
                <tr><td colspan="4">No se encontraron jugadores.</td></tr>
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
