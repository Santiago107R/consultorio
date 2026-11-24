<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: secretarios.php');
    exit;
}

$secretarioId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$secretarioId || $secretarioId < 1) {
    header('Location: secretarios.php?error=' . urlencode('El secretario seleccionado no es válido.'));
    exit;
}

try {
    $stmt = $conexion->prepare(
        "DELETE FROM usuarios WHERE id = ? AND rol = 'secretario'"
    );
    $stmt->bind_param('i', $secretarioId);
    $stmt->execute();
    $eliminado = $stmt->affected_rows;
    $stmt->close();

    if ($eliminado === 0) {
        header('Location: secretarios.php?error=' . urlencode('El secretario no existe.'));
        exit;
    }

    header('Location: secretarios.php?eliminado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    header('Location: secretarios.php?error=' . urlencode('No se pudo eliminar el secretario. Intentá de nuevo.'));
    exit;
}
