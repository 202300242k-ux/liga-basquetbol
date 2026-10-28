<?php
require_once __DIR__ . '/config/database.php';

$totalEquipos = (int)$pdo->query("SELECT COUNT(*) FROM equipos")->fetchColumn();
$totalJugadores = (int)$pdo->query("SELECT COUNT(*) FROM jugadores")->fetchColumn();
$totalPartidos = (int)$pdo->query("SELECT COUNT(*) FROM partidos")->fetchColumn();

$equipos = $pdo->query("SELECT e.id,e.nombre,e.ciudad,e.entrenador,COUNT(j.id) total_jugadores
 FROM equipos e LEFT JOIN jugadores j ON j.equipo_id=e.id
 GROUP BY e.id,e.nombre,e.ciudad,e.entrenador ORDER BY e.nombre LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

$jugadores = $pdo->query("SELECT j.nombres,j.apellidos,j.dorsal,j.posicion,e.nombre equipo
 FROM jugadores j INNER JOIN equipos e ON e.id=j.equipo_id
 ORDER BY e.nombre,j.apellidos,j.nombres LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);

$partidos = $pdo->query("SELECT p.fecha_hora,p.estado,p.puntos_local,p.puntos_visitante,
 el.nombre equipo_local,ev.nombre equipo_visitante
 FROM partidos p INNER JOIN equipos el ON el.id=p.equipo_local_id
 INNER JOIN equipos ev ON ev.id=p.equipo_visitante_id
 ORDER BY CASE WHEN p.estado='programado' THEN 0 ELSE 1 END,
 p.fecha_hora ASC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function fechaLiga($v): string { return $v ? date('d/m/Y · H:i', strtotime($v)) : 'Fecha por confirmar'; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liga de Básquetbol | Portal oficial</title>
<style>
:root{--navy:#101d38;--blue:#1d3156;--orange:#f97316;--bg:#f3f5f9;--text:#17233b;--muted:#6b778b;--border:#e4e9f1}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}body{font-family:"Segoe UI",Arial,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}a{text-decoration:none;color:inherit}
header{min-height:74px;background:var(--navy);color:white;padding:12px 6%;display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap}.brand{display:flex;align-items:center;gap:10px}.logo{width:43px;height:43px;border-radius:12px;background:var(--orange);display:grid;place-items:center;font-size:24px}.brand strong{display:block;font-size:14px}.brand small{display:block;font-size:11px;color:#bdc9dc}.nav{display:flex;gap:18px;flex-wrap:wrap}.nav a{font-size:12px;color:#e0e7f1;font-weight:650}.nav a:hover{color:#fb923c}.admin{border:1px solid #687792;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700}
.hero{min-height:420px;position:relative;display:flex;align-items:center;overflow:hidden;background:#101d38;color:white}.hero-img{position:absolute;inset:0 0 0 38%;background:url('https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=1800&q=85') center/cover;opacity:.75}.hero:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#101d38 0%,#101d38 35%,rgba(16,29,56,.83) 57%,rgba(16,29,56,.1))}.hero-content{position:relative;width:min(1100px,88%);margin:auto;padding:65px 0}.eyebrow{font-size:10px;letter-spacing:1.5px;color:#fdba74;font-weight:800}.pill{display:inline-block;border:1px solid #fb923c77;background:#f9731625;padding:5px 11px;border-radius:25px;margin-bottom:18px}.hero h1{font-size:clamp(34px,4.7vw,55px);line-height:1.13;letter-spacing:-1.2px;margin-bottom:16px}.hero h1 span{color:#fb923c}.hero p{font-size:14px;color:#d7dfec;max-width:570px;margin-bottom:23px}.btn{display:inline-block;background:var(--orange);color:#fff;padding:11px 16px;border-radius:8px;font-size:12px;font-weight:800;margin-right:7px}.btn-outline{border:1px solid #ffffff80;background:transparent}.wrap{max-width:1160px;margin:auto;padding:0 24px}.section{padding-top:36px}.section-head{margin-bottom:17px}.section-head h2{font-size:23px;line-height:1.3}.section-head p{font-size:12px;color:var(--muted);margin-top:4px}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:17px}.stat,.panel,.team,.info-card{background:white;border:1px solid var(--border);border-radius:13px;box-shadow:0 5px 18px #17203305}.stat{padding:20px}.stat-icon{width:42px;height:42px;border-radius:11px;display:grid;place-items:center;font-size:22px;margin-bottom:12px}.o{background:#fff0e5}.b{background:#e9efff}.g{background:#e5f8ed}.stat label{font-size:12px;color:var(--muted)}.stat strong{display:block;font-size:35px;line-height:1.3;color:var(--navy)}.stat small{font-size:11px;color:#8b96a7}.section-title-row{display:flex;justify-content:space-between;align-items:end;gap:12px;margin-bottom:16px}.more{font-size:12px;color:#ea580c;font-weight:800;white-space:nowrap}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:17px}.feature{height:235px;position:relative;border-radius:13px;overflow:hidden;color:white;display:flex;align-items:end;padding:20px;background:#1d3156}.feature img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:.3s}.feature:hover img{transform:scale(1.05)}.feature:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#0a1429 0%,transparent 100%)}.feature div{position:relative;z-index:1}.feature h3{font-size:22px}.feature p{font-size:12px;color:#e0e6ef}.feature span{display:inline-block;margin-top:10px;color:#fb923c;font-size:12px;font-weight:800}
.two{display:grid;grid-template-columns:1fr 1fr;gap:17px}.panel{padding:21px;min-width:0}.panel h2{font-size:19px}.panel-head{display:flex;align-items:center;justify-content:space-between;gap:10px;border-bottom:1px solid #edf0f5;padding-bottom:13px;margin-bottom:3px}.row{display:flex;align-items:center;gap:12px;padding:13px 0;border-bottom:1px solid #edf0f5}.row:last-child{border-bottom:0}.badge-icon{width:39px;height:39px;flex-shrink:0;border-radius:10px;background:#fff0e5;display:grid;place-items:center}.row-main{flex:1;min-width:0}.row-main strong{display:block;font-size:12px}.row-main small{display:block;color:#8590a2;font-size:10px}.tag{font-size:9px;padding:4px 7px;border-radius:20px;background:#e8f1ff;color:#2456a6;white-space:nowrap}.tag.final{background:#e5f8ed;color:#187448}.table-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:10px;background:white}.table{width:100%;border-collapse:collapse;min-width:540px}.table th{background:#f7f8fb;text-align:left;font-size:10px;color:#657188;text-transform:uppercase;padding:12px}.table td{font-size:12px;padding:12px;border-top:1px solid #edf0f5}.team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:13px}.team{padding:17px}.team .emoji{font-size:24px}.team h3{font-size:14px;margin-top:5px}.team p{font-size:11px;color:var(--muted)}.callout{margin:34px 0 40px;padding:28px;border-radius:14px;background:linear-gradient(110deg,#101d38,#263f68);color:white;display:flex;align-items:center;justify-content:space-between;gap:18px}.callout h2{font-size:22px}.callout p{font-size:12px;color:#d5deec;max-width:650px}.empty{font-size:12px;color:var(--muted);padding:20px 0}footer{background:var(--navy);color:#c4cede;padding:23px 6%;display:flex;align-items:center;justify-content:space-between;gap:15px;flex-wrap:wrap;font-size:11px}footer strong{color:white}
@media(max-width:800px){.nav{order:3;width:100%;justify-content:center}.hero-img{inset:0;opacity:.35}.hero:before{background:linear-gradient(90deg,rgba(16,29,56,.97),rgba(16,29,56,.55))}.two{grid-template-columns:1fr}.features,.team-grid{grid-template-columns:repeat(2,1fr)}.feature:last-child{grid-column:span 2}}
@media(max-width:560px){header{padding:12px 5%}.nav{gap:12px;justify-content:flex-start;overflow:auto}.nav a{white-space:nowrap}.admin{font-size:10px}.hero-content{padding:48px 0}.hero h1{font-size:35px}.wrap{padding:0 15px}.stats,.features,.team-grid{grid-template-columns:1fr}.feature:last-child{grid-column:auto}.feature{height:210px}.callout{align-items:flex-start;flex-direction:column;padding:22px}.section-head h2{font-size:21px}}
</style>
</head>
<body>
<header>
 <a class="brand" href="index.php"><span class="logo">🏀</span><span><strong>LIGA DE BÁSQUETBOL</strong><small>Portal oficial de competición</small></span></a>
 <nav class="nav"><a href="#equipos">Equipos</a><a href="#jugadores">Jugadores</a><a href="#partidos">Partidos</a><a href="#posiciones">Posiciones</a></nav>
 <a class="admin" href="admin/login.php">🔐 Modo administrador ↗</a>
</header>
<section class="hero"><div class="hero-img" role="img" aria-label="Partido de básquetbol"></div><div class="hero-content"><span class="pill eyebrow">TEMPORADA DEPORTIVA · <?= date('Y') ?></span><h1>La pasión por el básquetbol,<br><span>en un solo lugar.</span></h1><p>Explora los equipos, conoce a sus jugadores y mantente al día con el calendario y los resultados de la liga.</p><a class="btn" href="#resumen">Explorar la liga ↓</a><a class="btn btn-outline" href="#partidos">Ver partidos →</a></div></section>
<main class="wrap">
<section class="section" id="resumen"><div class="section-head"><span class="eyebrow">EN CIFRAS</span><h2>Resumen de la competición</h2><p>Información actualizada desde la base de datos del sistema.</p></div>
<div class="stats">
 <article class="stat"><div class="stat-icon o">🏆</div><label>Equipos inscritos</label><strong><?= $totalEquipos ?></strong><small>Clubes registrados en la liga</small></article>
 <article class="stat"><div class="stat-icon b">👥</div><label>Jugadores registrados</label><strong><?= $totalJugadores ?></strong><small>Participantes en las plantillas</small></article>
 <article class="stat"><div class="stat-icon g">🏀</div><label>Partidos registrados</label><strong><?= $totalPartidos ?></strong><small>Encuentros en el sistema</small></article>
</div></section>
<section class="section" id="explorar"><div class="section-title-row"><div class="section-head"><span class="eyebrow">DESCUBRE LA LIGA</span><h2>Explora el campeonato</h2><p>Accesos directos a la información deportiva.</p></div></div>
<div class="features">
<a class="feature" href="#equipos"><img src="https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=900&q=85" alt="Balón de básquetbol" loading="lazy"><div><span class="eyebrow">CLUBES PARTICIPANTES</span><h3>Equipos</h3><p>Conoce los clubes y sus ciudades.</p><span>Explorar equipos ↗</span></div></a>
<a class="feature" href="#jugadores"><img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=900&q=85" alt="Jugadores de básquetbol en cancha" loading="lazy"><div><span class="eyebrow">PLANTILLAS</span><h3>Jugadores</h3><p>Consulta dorsales y posiciones.</p><span>Ver jugadores ↗</span></div></a>
<a class="feature" href="#partidos"><img src="https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&w=900&q=85" alt="Cancha de básquetbol" loading="lazy"><div><span class="eyebrow">CALENDARIO</span><h3>Partidos y resultados</h3><p>Revisa fechas y marcadores.</p><span>Ver encuentros ↗</span></div></a>
</div></section>
<section class="section" id="equipos"><div class="section-title-row"><div class="section-head"><span class="eyebrow">CLUBES</span><h2>Equipos participantes</h2><p>Una muestra de los equipos registrados.</p></div><a class="more" href="equipos.php">Ver todos los equipos ↗</a></div>
<?php if($equipos): ?><div class="team-grid"><?php foreach($equipos as $e): ?><article class="team"><span class="emoji">🏆</span><h3><?= h($e['nombre']) ?></h3><p>📍 <?= h($e['ciudad'] ?: 'Ciudad no registrada') ?></p><p>👥 <?= (int)$e['total_jugadores'] ?> jugadores</p></article><?php endforeach; ?></div><?php else: ?><div class="empty">Todavía no hay equipos registrados.</div><?php endif; ?></section>
<section class="section" id="jugadores"><div class="section-title-row"><div class="section-head"><span class="eyebrow">PLANTILLAS</span><h2>Jugadores registrados</h2><p>Listado de muestra con dorsal, posición y equipo.</p></div><a class="more" href="jugadores.php">Ver todos los jugadores ↗</a></div>
<?php if($jugadores): ?><div class="table-wrap"><table class="table"><thead><tr><th>Dorsal</th><th>Jugador</th><th>Equipo</th><th>Posición</th></tr></thead><tbody><?php foreach($jugadores as $j): ?><tr><td><strong>#<?= h($j['dorsal'] ?? '—') ?></strong></td><td><?= h(trim($j['nombres'].' '.$j['apellidos'])) ?></td><td><?= h($j['equipo']) ?></td><td><?= h($j['posicion'] ?: 'No registrada') ?></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="empty">Todavía no hay jugadores registrados.</div><?php endif; ?></section>
<section class="section" id="partidos"><div class="section-title-row"><div class="section-head"><span class="eyebrow">AGENDA DEPORTIVA</span><h2>Partidos y resultados</h2><p>Próximos encuentros y marcadores almacenados.</p></div><a class="more" href="partidos.php">Ver todos los partidos ↗</a></div>
<?php if($partidos): ?><div class="panel"><?php foreach($partidos as $p): ?><div class="row"><div class="badge-icon">🏀</div><div class="row-main"><strong><?= h($p['equipo_local']) ?> &nbsp; vs. &nbsp; <?= h($p['equipo_visitante']) ?></strong><small><?= h(fechaLiga($p['fecha_hora'])) ?></small></div><?php if($p['estado']==='finalizado'): ?><strong><?= h($p['puntos_local']) ?> - <?= h($p['puntos_visitante']) ?></strong><span class="tag final">Finalizado</span><?php else: ?><span class="tag"><?= h(ucfirst($p['estado'])) ?></span><?php endif; ?></div><?php endforeach; ?></div><?php else: ?><div class="empty">Todavía no hay partidos registrados.</div><?php endif; ?></section>
<section class="callout" id="posiciones"><div><span class="eyebrow">CLASIFICACIÓN</span><h2>¿Cómo avanza el campeonato?</h2><p>Consulta la tabla de posiciones para comparar el desempeño de los equipos según los resultados registrados.</p></div><a class="btn" href="posiciones.php">Ver tabla de posiciones ↗</a></section>
</main>
<footer><div><strong>🏀 Liga de Básquetbol</strong><br>Portal de información deportiva</div><p>Sistema de gestión deportiva · <?= date('Y') ?></p><a href="admin/login.php">Acceso administrador</a></footer>
</body></html>