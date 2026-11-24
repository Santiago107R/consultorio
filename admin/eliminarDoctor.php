<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: doctores.php');
    exit;
}

$doctor_id = (int) ($_POST['id'] ?? 0);

function volver(string $error): void
{
    header('Location: doctores.php?error=' . urlencode($error));
    exit;
}

if ($doctor_id <= 0) {
    volver('ID de doctor inválido.');
}

try {
    $conexion->begin_transaction();

    // Obtener usuario_id para saber si el doctor existe
    $stmt = $conexion->prepare("SELECT usuario_id FROM doctores WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $doctor = $resultado->fetch_assoc();
    $stmt->close();

    if (!$doctor) {
        throw new Exception('El doctor no existe.');
    }

    $usuario_id = (int) $doctor['usuario_id'];

    $stmt = $conexion->prepare("DELETE FROM doctor_especialidad WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare("DELETE FROM agenda_fija WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare("DELETE FROM doctores WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();

   
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    $conexion->commit();

    header('Location: doctores.php');
    exit;

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();

    if ($e->getCode() === 1451) {
        volver('No se puede eliminar el doctor porque tiene registros asociados (como turnos).');
    }

    error_log($e->getMessage());
    volver('No se pudo eliminar el doctor. Intentá de nuevo.');

} catch (Exception $e) {
    $conexion->rollback();
    volver($e->getMessage());
}