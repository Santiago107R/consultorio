<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';

if (empty($_GET['id']) || empty($_GET['estado']) || ($_GET['estado'] !== '5' && $_GET['estado'] !== '7')) {
    header("Location: " . base_url);
    exit();
}

$idTurno = $_GET['id'];
$idEstado = $_GET['estado'];

$query = "UPDATE turnos SET estado_id = ? WHERE id = ?";

require_once __DIR__ . '/../config/conexion.php';

$stmt = $conexion->prepare($query);
$stmt->bind_param('ii', $idEstado, $idTurno);
$stmt->execute();
$stmt->close();

header("Location: " . base_url);