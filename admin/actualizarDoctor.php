<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: doctores.php');
    exit;
}

$doctor_id  = (int) ($_POST['id'] ?? 0);
$usuario_id = (int) ($_POST['usuario_id'] ?? 0);
$nombre     = trim($_POST['nombre'] ?? '');
$email      = trim($_POST['email'] ?? '');

function volver(string $error, int $doctor_id): void
{
    header('Location: editarDoctor.php?id=' . $doctor_id . '&error=' . urlencode($error));
    exit;
}

if ($doctor_id <= 0 || $usuario_id <= 0) {
    header('Location: doctores.php');
    exit;
}

if ($nombre === '' || $email === '') {
    volver('Completá todos los campos.', $doctor_id);
}
if (mb_strlen($nombre) > 25) {
    volver('El nombre no puede tener más de 25 caracteres.', $doctor_id);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    volver('El email no es válido.', $doctor_id);
}

try {
    $conexion->begin_transaction();

    $stmtDoc = $conexion->prepare("SELECT id FROM doctores WHERE id = ? AND usuario_id = ?");
    $stmtDoc->bind_param("ii", $doctor_id, $usuario_id);
    $stmtDoc->execute();
    if ($stmtDoc->get_result()->num_rows === 0) {
        throw new Exception('El doctor no existe.');
    }
    $stmtDoc->close();

    $stmt = $conexion->prepare("UPDATE usuarios SET email = ?, nombre = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $nombre, $usuario_id);
    $stmt->execute();
    $stmt->close();

    $conexion->commit();

    header('Location: doctores.php');
    exit;

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();

    if ($e->getCode() === 1062) {
        volver('Ya existe un usuario con ese email.', $doctor_id);
    }
    error_log($e->getMessage());
    volver('No se pudo actualizar el doctor. Intentá de nuevo.', $doctor_id);

} catch (Exception $e) {
    $conexion->rollback();
    volver($e->getMessage(), $doctor_id);
}