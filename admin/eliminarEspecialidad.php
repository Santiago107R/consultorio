<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: especialidades.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

function volver(string $error): void
{
    header('Location: especialidades.php?error=' . urlencode($error));
    exit;
}

if ($id <= 0) {
    volver('ID de especialidad inválido.');
}

try {
    $conexion->begin_transaction();

    $stmtCheck = $conexion->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    if ($stmtCheck->get_result()->num_rows === 0) {
        throw new Exception('La especialidad no existe.');
    }
    $stmtCheck->close();

    // Quitar relación con doctores antes de eliminar la especialidad
    $stmtRel = $conexion->prepare("DELETE FROM doctor_especialidad WHERE especialidad_id = ?");
    $stmtRel->bind_param("i", $id);
    $stmtRel->execute();
    $stmtRel->close();

    $stmtDel = $conexion->prepare("DELETE FROM especialidades WHERE id = ?");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
    $stmtDel->close();

    $conexion->commit();

    header('Location: especialidades.php');
    exit;

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();
    if ($e->getCode() === 1451) {
        volver('No se puede eliminar la especialidad porque tiene registros asociados.');
    }
    error_log($e->getMessage());
    volver('No se pudo eliminar la especialidad. Intentá de nuevo.');

} catch (Exception $e) {
    $conexion->rollback();
    volver($e->getMessage());
}