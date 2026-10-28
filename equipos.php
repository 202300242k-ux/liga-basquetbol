
<?php
require_once __DIR__ . '/config/database.php';

function h($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$busqueda = trim($_GET['q'] ?? '');

$sql = "SELECT e.id, e.nombre, e.ciudad, e.entrenador,
               COUNT(j.id) AS total_jugadores
        FROM equipos e
        LEFT JOIN jugadores j ON j.equipo_id = e.id";

$params = [];

if ($busqueda !== '') {
    $sql .= " WHERE e.nombre LIKE ?
              OR e.ciudad LIKE ?
              OR e.entrenador LIKE ?";
    $filtro = "%{$busqueda}%";
    $params = [$filtro, $filtro, $filtro];
}

$sql .= " GROUP BY e.id, e.nombre, e.ciudad, e.entrenador
          ORDER BY e.nombre";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipos | Liga de Básquetbol</title>
    <style>
        body{margin:0;background:#f3f5f9;color:#17233b;font-family:Arial,sans-serif}
        header,footer{background:#101d38;color:white;padding:20px 6%}
        header a,footer a{color:white;text-decoration:none;margin-right:14px}
        main{max-width:1100px;margin:auto;padding:30px 20px}
        h1{margin-top:0}
        form{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}
        input{padding:11px;border:1px solid #dce2eb;border-radius:7px;flex:1;min-width:200px}
        button{background:#f97316;color:white;border:0;border-radius:7px;padding:11px 16px;cursor:pointer}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px}
        .card{background:white;border:1px solid #e4e9f1;border-radius:12px;padding:20px}
        .card h2{font-size:19px}.muted{color:#6b778b;font-size:14px}
        .link{color:#ea580c;font-weight:bold;text-decoration:none}
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
    <h1>Equipos participantes</h1>
    <p class="muted">Consulta los clubes y sus plantillas.</p>

    <form method="get">
        <input type="search" name="q"
               value="<?= h($busqueda) ?>"
               placeholder="Buscar por equipo, ciudad o entrenador">
        <button type="submit">Buscar</button>
    </form>

    <p class="muted"><?= count($equipos) ?> equipo(s) encontrado(s).</p>

    <?php if ($equipos): ?>
        <section class="grid">
            <?php foreach ($equipos as $equipo): ?>
                <article class="card">
                    <h2>🏆 <?= h($equipo['nombre']) ?></h2>
                    <p>📍 Ciudad: <?= h($equipo['ciudad'] ?: 'No registrada') ?></p>
                    <p>Entrenador: <?= h($equipo['entrenador'] ?: 'No registrado') ?></p>
                    <p>👥 Jugadores: <?= (int) $equipo['total_jugadores'] ?></p>
                    <a class="link"
                       href="jugadores.php?equipo_id=<?= (int) $equipo['id'] ?>">
                        Ver plantilla →
                    </a>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <p>No se encontraron equipos.</p>
    <?php endif; ?>
</main>

<footer>
    <a href="index.php">Volver al inicio</a>
    <a href="admin/login.php">Modo administrador</a>
</footer>
</body>
</html>
