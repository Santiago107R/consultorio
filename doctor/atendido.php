<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

$idTurno = (int) ($_POST['turno_id'] ?? 0);
$entrada = trim($_POST['nueva_entrada'] ?? '');
$idDoctor = $_SESSION['usuario']['doctor_id'] ?? 0;
$estado = 3;

if ($idTurno && $entrada !== '' && $idDoctor) {
    $stmt = $conexion->prepare(
        "UPDATE pacientes AS p
         INNER JOIN turnos AS t ON t.paciente_id = p.id
         SET p.historia_clinica = CONCAT_WS(CHAR(10), p.historia_clinica, ?),
             t.estado_id = ?
         WHERE t.id = ? AND t.doctor_id = ? AND t.fecha <= CURDATE()"
    );
    $stmt->bind_param('siii', $entrada, $estado, $idTurno, $idDoctor);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();