<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Ruta incorrecta'
    ]);
    exit;
}

try {
    require_once __DIR__ . '/conexion.php';
    $query = $conexion->query(
        'SELECT usuario.nombre AS nombre, usuario.email AS email, doctor.agenda_fija AS agenda_fija, doctor.id AS id
         FROM doctores AS doctor 
         LEFT JOIN usuarios AS usuario ON usuario.id = doctor.usuario_id 
         ORDER BY doctor.id ASC'
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
    'doctores' => $doctores
]);