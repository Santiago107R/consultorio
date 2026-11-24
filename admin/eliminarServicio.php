<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: servicios.php');
    exit;
}

$servicioId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$servicioId || $servicioId < 1) {
    header('Location: servicios.php?error=' . urlencode('El servicio seleccionado no es válido.'));
    exit;
}

try {
    $stmt = $conexion->prepare('DELETE FROM servicios WHERE id = ?');
    $stmt->bind_param('i', $servicioId);
    $stmt->execute();
    $eliminado = $stmt->affected_rows;
    $stmt->close();

    if ($eliminado === 0) {
        header('Location: servicios.php?error=' . urlencode('El servicio no existe.'));
        exit;
    }

    header('Location: servicios.php?eliminado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    if ($error->getCode() === 1451) {
        header('Location: servicios.php?error=' . urlencode('No se puede eliminar el servicio porque tiene turnos actuales o históricos asociados.'));
        exit;
    }

    header('Location: servicios.php?error=' . urlencode('No se pudo eliminar el servicio. Intentá de nuevo.'));
    exit;
}
