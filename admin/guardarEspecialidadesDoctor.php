<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: doctores.php');
    exit;
}

$doctor_id = (int) ($_POST['doctor_id'] ?? 0);
$accion    = $_POST['accion'] ?? '';

function volver(string $error, int $doctor_id): void
{
    header('Location: especialidadesDoctor.php?id=' . $doctor_id . '&error=' . urlencode($error));
    exit;
}

if ($doctor_id <= 0) {
    header('Location: doctores.php');
    exit;
}

try {
    $conexion->begin_transaction();

    $stmtDoc = $conexion->prepare("SELECT id FROM doctores WHERE id = ?");
    $stmtDoc->bind_param("i", $doctor_id);
    $stmtDoc->execute();
    if ($stmtDoc->get_result()->num_rows === 0) {
        throw new Exception('El doctor no existe.');
    }
    $stmtDoc->close();

    if ($accion === 'agregar') {
        $especialidades = array_unique(array_map('intval', $_POST['especialidades'] ?? []));
        $especialidades = array_filter($especialidades, fn($id) => $id > 0);

        if (empty($especialidades)) {
            throw new Exception('Seleccioná al menos una especialidad.');
        }

        $stmtCheck = $conexion->prepare("SELECT id FROM especialidades WHERE id = ?");
        $stmtIns   = $conexion->prepare("INSERT IGNORE INTO doctor_especialidad (doctor_id, especialidad_id) VALUES (?, ?)");

        foreach ($especialidades as $esp_id) {
            $stmtCheck->bind_param("i", $esp_id);
            $stmtCheck->execute();
            if ($stmtCheck->get_result()->num_rows === 0) {
                throw new Exception('Especialidad inválida.');
            }

            $stmtIns->bind_param("ii", $doctor_id, $esp_id);
            $stmtIns->execute();
        }
        $stmtCheck->close();
        $stmtIns->close();

    } elseif ($accion === 'quitar') {
        $especialidad_id = (int) ($_POST['especialidad_id'] ?? 0);
        if ($especialidad_id <= 0) {
            throw new Exception('Especialidad inválida.');
        }

        $stmtDel = $conexion->prepare("DELETE FROM doctor_especialidad WHERE doctor_id = ? AND especialidad_id = ?");
        $stmtDel->bind_param("ii", $doctor_id, $especialidad_id);
        $stmtDel->execute();
        $stmtDel->close();

    } else {
        throw new Exception('Acción no válida.');
    }

    $conexion->commit();
    header('Location: especialidadesDoctor.php?id=' . $doctor_id);
    exit;

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();
    error_log($e->getMessage());
    volver('No se pudieron actualizar las especialidades.', $doctor_id);

} catch (Exception $e) {
    $conexion->rollback();
    volver($e->getMessage(), $doctor_id);
}