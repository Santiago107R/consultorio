<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crearSecretario.php');
    exit;
}

function volver(string $error): void
{
    header('Location: crearSecretario.php?error=' . urlencode($error));
    exit;
}

$nombre     = trim($_POST['nombre'] ?? '');
$email      = trim($_POST['email'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

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

$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$rol  = 'secretario';

try {
    $stmt = $conexion->prepare("INSERT INTO usuarios (email, nombre, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $nombre, $hash, $rol);
    $stmt->execute();
    $stmt->close();

    header('Location: secretarios.php');
    exit;

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        volver('Ya existe un usuario con ese email.');
    }
    error_log($e->getMessage());
    volver('No se pudo crear el secretario. Intentá de nuevo.');
}