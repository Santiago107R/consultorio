<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$pacienteId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';
$dni = is_string($_POST['dni'] ?? null) ? trim($_POST['dni']) : '';

function volverAEditarPaciente(string $error, int $pacienteId): void
{
    header('Location: editarPaciente.php?' . http_build_query([
        'id' => $pacienteId,
        'error' => $error,
    ]));
    exit;
}

if (!$pacienteId || $pacienteId < 1) {
    header('Location: index.php');
    exit;
}

if ($nombre === '' || $dni === '') {
    volverAEditarPaciente('Completá el nombre y el DNI.', $pacienteId);
}
if (mb_strlen($nombre) > 25) {
    volverAEditarPaciente('El nombre no puede tener más de 25 caracteres.', $pacienteId);
}
if (mb_strlen($dni) > 100) {
    volverAEditarPaciente('El DNI no puede tener más de 100 caracteres.', $pacienteId);
}

$conexion->begin_transaction();

try {
    $stmtPaciente = $conexion->prepare(
        "SELECT p.usuario_id
         FROM pacientes AS p
         WHERE p.id = ?
         FOR UPDATE"
    );
    $stmtPaciente->bind_param('i', $pacienteId);
    $stmtPaciente->execute();
    $paciente = $stmtPaciente->get_result()->fetch_assoc();
    $stmtPaciente->close();

    if (!$paciente) {
        $conexion->rollback();
        header('Location: index.php?error=' . urlencode('El paciente no existe.'));
        exit;
    }

    $usuarioId = (int) $paciente['usuario_id'];

    $stmtUsuario = $conexion->prepare(
        'UPDATE usuarios SET nombre = ? WHERE id = ?'
    );
    $stmtUsuario->bind_param('si', $nombre, $usuarioId);
    $stmtUsuario->execute();
    $stmtUsuario->close();

    $stmtActualizarPaciente = $conexion->prepare(
        'UPDATE pacientes SET dni = ? WHERE id = ?'
    );
    $stmtActualizarPaciente->bind_param('si', $dni, $pacienteId);
    $stmtActualizarPaciente->execute();
    $stmtActualizarPaciente->close();

    $conexion->commit();
    header('Location: index.php?actualizado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    $conexion->rollback();
    error_log($error->getMessage());
    volverAEditarPaciente('No se pudieron guardar los cambios. Intentá de nuevo.', $pacienteId);
}
