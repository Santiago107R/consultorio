<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	http_response_code(405);
	echo json_encode([
		'ok' => false,
		'mensaje' => 'No se que poner.'
	]);
	exit;
}

include './conexion.php';

if ($conexion->connect_error) {
	http_response_code(500);
	echo json_encode([
		'ok' => false,
		'mensaje' => 'No se pudo conectar con la base de datos.'
	]);
	exit;
}

$consulta = $conexion->query(
	'SELECT id, usuario_id FROM pacientes ORDER BY id ASC'
);

if (!$consulta) {
	http_response_code(500);
	echo json_encode([
		'ok' => false,
		'mensaje' => 'No se pudieron obtener los pacientes.'
	]);
	exit;
}

$pacientes = $consulta->fetch_all(MYSQLI_ASSOC);

echo json_encode([
	'ok' => true,
	'pacientes' => $pacientes
]);
