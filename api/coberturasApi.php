<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

try {
    require_once __DIR__ . '/conexion.php';

    $queryCob = $conexion->query('SELECT id, nombre FROM coberturas_medica ORDER BY id ASC');
    $coberturas = $queryCob->fetch_all(MYSQLI_ASSOC);
    
    $queryPlanes = $conexion->query('SELECT id, nombre, cobertura_id FROM planes ORDER BY id ASC');
    $planes = $queryPlanes->fetch_all(MYSQLI_ASSOC);

    foreach ($coberturas as &$cob) {
        $cob['planes'] = array_values(array_filter($planes, function($plan) use ($cob) {
            return $plan['cobertura_id'] == $cob['id'];
        }));
    }
    unset($cob);

} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No se pudieron obtener las coberturas médicas.'
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'coberturas' => $coberturas
]);
