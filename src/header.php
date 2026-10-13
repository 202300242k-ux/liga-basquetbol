<?php
if (!isset($pageTitle)) $pageTitle = 'Liga de Básquetbol';
$base = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if (substr($base, -6) === '/admin') $base = substr($base, 0, -6);
if ($base === '/') $base = '';
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= htmlspecialchars($pageTitle) ?> | Liga de Básquetbol</title><link rel="stylesheet" href="<?= $base ?>/public/css/style.css"></head><body>
<header class="site-header"><a class="brand" href="<?= $base ?>/index.php"><span class="brand-icon">🏀</span><span>LIGA<span class="brand-light">BÁSQUET</span></span></a><nav class="main-nav"><a href="<?= $base ?>/index.php">Inicio</a><a href="<?= $base ?>/equipos.php">Equipos</a><a href="<?= $base ?>/partidos.php">Partidos</a><a href="<?= $base ?>/tabla.php">Posiciones</a><a class="nav-admin" href="<?= $base ?>/admin/login.php">Administración ↗</a></nav></header><main class="container">
