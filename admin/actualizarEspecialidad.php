<?php
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: especialidades.php');
    exit;
}

$id     = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');

function volver(string $error, int $id): void
{
    header('Location: editarEspecialidad.php?id=' . $id . '&error=' . urlencode($error));
    exit;
}

if ($id <= 0) {
    header('Location: especialidades.php');
    exit;
}

if ($nombre === '') {
    volver('Completá el nombre de la especialidad.', $id);
}
if (mb_strlen($nombre) > 50) {
    volver('El nombre no puede tener más de 50 caracteres.', $id);
}

try {
    $stmt = $conexion->prepare("UPDATE especialidades SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: especialidades.php');
    exit;

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        volver('Ya existe una especialidad con ese nombre.', $id);
    }
    error_log($e->getMessage());
    volver('No se pudo actualizar la especialidad. Intentá de nuevo.', $id);
}