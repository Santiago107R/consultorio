<?php
header('Content-Type: application/json; charset=utf-8');

include './conexion.php';

$respuesta = [
    'ok' => false,
    'mensaje' => ''
];

$email = trim($_POST['email'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (empty($email) || empty($contrasena)) {
    http_response_code(400);
    $respuesta['mensaje'] = 'Debés completar email y contraseña.';
    echo json_encode($respuesta);
    exit;
}

$query = "SELECT email, nombre, contrasena, rol FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($query);

if (!$stmt) {
    http_response_code(500);
    $respuesta['mensaje'] = 'Error interno del servidor.';
    echo json_encode($respuesta);
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
    http_response_code(401);
    $respuesta['mensaje'] = 'Email o contraseña incorrectos.';
    echo json_encode($respuesta);
    exit;
}

unset($usuario['contrasena']);

$respuesta['ok'] = true;
$respuesta['mensaje'] = 'Login correcto.';
// $respuesta['usuario'] = $usuario;

session_start();
$_SESSION['usuario'] = $usuario;

echo json_encode($respuesta);
