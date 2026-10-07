<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Método no permitido.'
    ]);
    exit;
}

try {
    require_once __DIR__ . '/conexion.php';
    $query = $conexion->query(
        'SELECT id, nombre FROM servicios ORDER BY id ASC'
    );

    $servicios = $query->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No se pudieron obtener los servicios.'
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'mensaje' => 'ok',
    'servicios' => $servicios
]);
