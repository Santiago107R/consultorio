<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Método no permitido'
    ]);
    exit;
}

try {
    require_once __DIR__ . '/conexion.php';


    $queryEsp = $conexion->query('SELECT id, nombre FROM especialidades ORDER BY id ASC');
    $especialidades = $queryEsp->fetch_all(MYSQLI_ASSOC);


    $queryDoc = $conexion->query(
        'SELECT 
            de.especialidad_id, 
            d.id AS doctor_id,
            u.nombre AS doctor_nombre
         FROM doctores AS d
         INNER JOIN usuarios AS u ON u.id = d.usuario_id
         INNER JOIN doctor_especialidad AS de ON de.doctor_id = d.id'
    );
    $doctores = $queryDoc->fetch_all(MYSQLI_ASSOC);

    foreach ($especialidades as &$esp) {
        $esp['doctores'] = array_values(array_filter($doctores, function($doc) use ($esp) {
            return $doc['especialidad_id'] == $esp['id'];
        }));
    }
    unset($esp);

} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No se pudieron obtener las especialidades.'
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'especialidades' => $especialidades
]);