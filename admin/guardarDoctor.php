<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crearDoctor.php');
    exit;
}

function volver(string $error): void
{
    header('Location: crearDoctor.php?error=' . urlencode($error));
    exit;
}

$nombre       = trim($_POST['nombre'] ?? '');
$email        = trim($_POST['email'] ?? '');
$contrasena   = $_POST['contrasena'] ?? '';
$especialidades = array_unique(array_map('intval', $_POST['especialidades'] ?? []));
$especialidades = array_filter($especialidades, fn($id) => $id > 0);

if ($nombre === '' || $email === '' || $contrasena === '') {
    volver('Completá todos los campos.');
}
if (mb_strlen($nombre) > 25) {
    volver('El nombre no puede tener más de 25 caracteres.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    volver('El email no es válido.');
}
if (strlen($contrasena) < 6) {
    volver('La contraseña debe tener al menos 6 caracteres.');
}
if (empty($especialidades)) {
    volver('Elegí al menos una especialidad.');
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

try {
    $conexion->begin_transaction();

    $rol = 'doctor';
    $stmt = $conexion->prepare("INSERT INTO usuarios (email, nombre, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $nombre, $hash, $rol);
    $stmt->execute();
    $usuario_id = $conexion->insert_id;
    $stmt->close();

    $stmt = $conexion->prepare("INSERT INTO doctores (usuario_id) VALUES (?)");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $doctor_id = $conexion->insert_id;
    $stmt->close();

    $stmtCheck = $conexion->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmtIns   = $conexion->prepare("INSERT INTO doctor_especialidad (doctor_id, especialidad_id) VALUES (?, ?)");

    foreach ($especialidades as $especialidad_id) {
        $stmtCheck->bind_param("i", $especialidad_id);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows === 0) {
            throw new Exception('Especialidad inválida.');
        }

        $stmtIns->bind_param("ii", $doctor_id, $especialidad_id);
        $stmtIns->execute();
    }
    $stmtCheck->close();
    $stmtIns->close();

    $conexion->commit();

    header('Location: doctores.php');
    exit;

} catch (mysqli_sql_exception $e) {
    $conexion->rollback();

    if ($e->getCode() === 1062) {
        volver('Ya existe un usuario con ese email.');
    }
    error_log($e->getMessage());
    volver('No se pudo crear el doctor. Intentá de nuevo.');

} catch (Exception $e) {
    $conexion->rollback();
    volver($e->getMessage());
}