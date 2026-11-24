<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: servicios.php');
    exit;
}

$servicioId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';
$horaInicio = is_string($_POST['hora_inicio'] ?? null) ? $_POST['hora_inicio'] : '';
$horaFin = is_string($_POST['hora_fin'] ?? null) ? $_POST['hora_fin'] : '';
$diasRecibidos = $_POST['dias'] ?? [];
$diasValidos = ['0', '1', '2', '3', '4', '5', '6'];

function volverAEditarServicio(string $error, int $servicioId): void
{
    header('Location: editarServicio.php?' . http_build_query([
        'id' => $servicioId,
        'error' => $error,
    ]));
    exit;
}

if (!$servicioId || $servicioId < 1) {
    header('Location: servicios.php');
    exit;
}

if ($nombre === '') {
    volverAEditarServicio('Completá el nombre del servicio.', $servicioId);
}
if (mb_strlen($nombre) > 255) {
    volverAEditarServicio('El nombre no puede tener más de 255 caracteres.', $servicioId);
}
if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaInicio)
    || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaFin)
    || $horaFin <= $horaInicio) {
    volverAEditarServicio('Ingresá un rango horario válido; la hora de fin debe ser posterior al inicio.', $servicioId);
}
if (!is_array($diasRecibidos)) {
    volverAEditarServicio('Seleccioná al menos un día laborable.', $servicioId);
}

$dias = [];
foreach ($diasRecibidos as $dia) {
    if (!is_string($dia) || !in_array($dia, $diasValidos, true)) {
        volverAEditarServicio('La selección de días no es válida.', $servicioId);
    }
    $dias[] = $dia;
}
$dias = array_values(array_unique($dias));
sort($dias, SORT_NUMERIC);

if ($dias === []) {
    volverAEditarServicio('Seleccioná al menos un día laborable.', $servicioId);
}

try {
    $diasLaborables = implode(',', $dias);
    $stmt = $conexion->prepare(
        'UPDATE servicios
         SET nombre = ?, dias_laborables = ?, hora_inicio = ?, hora_fin = ?
         WHERE id = ?'
    );
    $stmt->bind_param('ssssi', $nombre, $diasLaborables, $horaInicio, $horaFin, $servicioId);
    $stmt->execute();
    $actualizado = $stmt->affected_rows;
    $stmt->close();

    if ($actualizado === 0) {
        $stmtExiste = $conexion->prepare('SELECT id FROM servicios WHERE id = ?');
        $stmtExiste->bind_param('i', $servicioId);
        $stmtExiste->execute();
        $existe = $stmtExiste->get_result()->num_rows > 0;
        $stmtExiste->close();

        if (!$existe) {
            header('Location: servicios.php?error=' . urlencode('El servicio no existe.'));
            exit;
        }
    }

    header('Location: servicios.php?actualizado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    volverAEditarServicio('No se pudo actualizar el servicio. Intentá de nuevo.', $servicioId);
}
