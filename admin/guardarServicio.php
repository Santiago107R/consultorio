<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crearServicio.php');
    exit;
}

$nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';
$horaInicio = is_string($_POST['hora_inicio'] ?? null) ? $_POST['hora_inicio'] : '';
$horaFin = is_string($_POST['hora_fin'] ?? null) ? $_POST['hora_fin'] : '';
$diasRecibidos = $_POST['dias'] ?? [];
$diasValidos = ['0', '1', '2', '3', '4', '5', '6'];

function volverACrearServicio(string $error): void
{
    header('Location: crearServicio.php?error=' . urlencode($error));
    exit;
}

if ($nombre === '') {
    volverACrearServicio('Completá el nombre del servicio.');
}
if (mb_strlen($nombre) > 255) {
    volverACrearServicio('El nombre no puede tener más de 255 caracteres.');
}
if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaInicio)
    || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaFin)
    || $horaFin <= $horaInicio) {
    volverACrearServicio('Ingresá un rango horario válido; la hora de fin debe ser posterior al inicio.');
}
if (!is_array($diasRecibidos)) {
    volverACrearServicio('Seleccioná al menos un día laborable.');
}

$dias = [];
foreach ($diasRecibidos as $dia) {
    if (!is_string($dia) || !in_array($dia, $diasValidos, true)) {
        volverACrearServicio('La selección de días no es válida.');
    }
    $dias[] = $dia;
}
$dias = array_values(array_unique($dias));
sort($dias, SORT_NUMERIC);

if ($dias === []) {
    volverACrearServicio('Seleccioná al menos un día laborable.');
}

try {
    $diasLaborables = implode(',', $dias);
    $stmt = $conexion->prepare(
        'INSERT INTO servicios (nombre, dias_laborables, hora_inicio, hora_fin)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->bind_param('ssss', $nombre, $diasLaborables, $horaInicio, $horaFin);
    $stmt->execute();
    $stmt->close();

    header('Location: servicios.php?creado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    volverACrearServicio('No se pudo crear el servicio. Intentá de nuevo.');
}
