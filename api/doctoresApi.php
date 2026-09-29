<?php
header('Content-Type: application/json; charset=utf-8');

include './conexion.php';

$respuesta = [
    'ok' => false,
    'mensaje' => ''
];

$query = 'SELECT id, usuario_id FROM doctores';
$stmt = $conexion->prepare($query);

if (!$stmt) {
    http_response_code(500);
    $respuesta['mensaje'] = 'Error del servidor.';
    echo json_encode($respuesta);
    exit;
}

$stmt->execute();
$resultado = $stmt->get_result();
$doctores = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();

http_response_code(200);
$respuesta['ok'] = true;
$respuesta['mensaje'] = 'ok';
$respuesta['doctores'] = $doctores;

echo json_encode($respuesta);