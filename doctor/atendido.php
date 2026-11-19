<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$idTurno  = (int) ($_POST['turno_id'] ?? 0);
$entrada  = trim($_POST['nueva_entrada'] ?? '');
$idDoctor = (int) ($_SESSION['usuario']['doctor_id'] ?? 0);
$estadoAtendido = 3;

if (!$idTurno || $entrada === '' || !$idDoctor) {
    header("Location: index.php");
    exit();
}

try {
    $conexion->begin_transaction();

    $stmt = $conexion->prepare(
        "SELECT paciente_id
         FROM turnos
         WHERE id = ? AND doctor_id = ? AND fecha <= CURDATE()
         FOR UPDATE"
    );
    $stmt->bind_param('ii', $idTurno, $idDoctor);
    $stmt->execute();
    $turno = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$turno) {
        $conexion->rollback();
        header("Location: index.php");
        exit();
    }

    $stmt = $conexion->prepare(
        "UPDATE pacientes
         SET historia_clinica = CONCAT_WS(CHAR(10), historia_clinica, ?)
         WHERE id = ?"
    );
    $stmt->bind_param('si', $entrada, $turno['paciente_id']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare(
        "INSERT INTO turnos_historicos
            (fecha, hora, paciente_id, especialidad_id, servicio_id, doctor_id, consultorio_id, estado_id)
         SELECT fecha, hora, paciente_id, especialidad_id, servicio_id, doctor_id, consultorio_id, ?
         FROM turnos
         WHERE id = ?"
    );
    $stmt->bind_param('ii', $estadoAtendido, $idTurno);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare("DELETE FROM turnos WHERE id = ?");
    $stmt->bind_param('i', $idTurno);
    $stmt->execute();
    $stmt->close();

    $conexion->commit();

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();
    error_log($e->getMessage());
}

header("Location: index.php");
exit();