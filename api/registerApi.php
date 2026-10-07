<?php
header('Content-Type: application/json; charset=utf-8');

include './conexion.php';

$respuesta = [
    'ok' => false,
    'mensaje' => ''
];

$email = trim($_POST['email'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$rol = "paciente";
$contrasena = $_POST['contrasena'] ?? '';

if (empty($email) || empty($nombre) || empty($dni) || empty($contrasena)) {
    http_response_code(400);
    $respuesta['mensaje'] = 'Debés completar todos los campos.';
    echo json_encode($respuesta);
    exit;
}

$nombre = ucfirst($nombre);
$contrasenaHashed = password_hash($contrasena, PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (email, nombre, contrasena, rol) VALUES(?, ?, ?, ?)";
$queryPaciente = "INSERT INTO pacientes (usuario_id, dni) VALUES(?, ?)";
$stmt = $conexion->prepare($query);

if (!$stmt) {
    http_response_code(500);
    $respuesta['mensaje'] = 'Error interno del servidor.';
    echo json_encode($respuesta);
    exit;
}

$stmt->bind_param('ssss', $email, $nombre, $contrasenaHashed, $rol);
$stmt->execute();
$id_ingresado = $stmt->insert_id;
$stmt->close();

$stmtPaciente = $conexion->prepare($queryPaciente);
 
if (!$stmtPaciente) {
    http_response_code(500);
    $respuesta['mensaje'] = 'Error interno del servidor.';
    echo json_encode($respuesta);
    exit;
}

$stmtPaciente->bind_param('is', $id_ingresado, $dni);
$stmtPaciente->execute();
$stmtPaciente->close();


$respuesta['ok'] = true;
$respuesta['mensaje'] = 'Register correcto.';

echo json_encode($respuesta);
