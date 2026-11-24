<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crearEspecialidad.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');

function volver(string $error): void
{
    header('Location: crearEspecialidad.php?error=' . urlencode($error));
    exit;
}

if ($nombre === '') {
    volver('Completá el nombre de la especialidad.');
}
if (mb_strlen($nombre) > 50) {
    volver('El nombre no puede tener más de 50 caracteres.');
}

try {
    $stmt = $conexion->prepare("INSERT INTO especialidades (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $stmt->close();

    header('Location: especialidades.php');
    exit;

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        volver('Ya existe una especialidad con ese nombre.');
    }
    error_log($e->getMessage());
    volver('No se pudo crear la especialidad. Intentá de nuevo.');
}