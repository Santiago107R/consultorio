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
        'SELECT id, usuario_id FROM doctores ORDER BY id ASC'
    );

    $doctores = $query->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No se pudieron obtener los doctores.'
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'mensaje' => 'ok',
    'doctores' => $doctores
]);