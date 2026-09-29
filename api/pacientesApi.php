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
		'SELECT usuario.nombre AS nombre, cobertura.nombre AS cobertura, paciente.dni AS dni FROM pacientes AS paciente LEFT JOIN usuarios AS usuario ON usuario.id = paciente.usuario_id LEFT JOIN coberturas_medica AS cobertura ON cobertura.id = paciente.cobertura_id ORDER BY paciente.id ASC'
	);

	$pacientes = $query->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
	http_response_code(500);
	echo json_encode([
		'ok' => false,
		'mensaje' => 'No se pudieron obtener los pacientes.'
	]);
	exit;
}

echo json_encode([
	'ok' => true,
	'pacientes' => $pacientes
]);
