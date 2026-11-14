<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';

if (empty($_POST['turno_id']) || empty($_POST['consultorio_id'])) {
    header('Location: ' . base_url);
    exit();
}


$idTurno = $_POST['turno_id'];
$idConsultorio = $_POST['consultorio_id'];

$query = "UPDATE turnos SET consultorio_id = ? WHERE id = ?";

require_once __DIR__ . '/../config/conexion.php';

$stmt = $conexion->prepare($query);
$stmt->bind_param('ii', $idConsultorio, $idTurno);
$stmt->execute();
$stmt->close();

header("Location: " . base_url);