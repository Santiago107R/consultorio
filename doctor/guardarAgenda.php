<?php
    require_once './layouts/header.php';
    require_once __DIR__ . '/../config/conexion.php';


    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['usuario']['doctor_id'])) {
        header("Location: " . base_url);
        exit();
    }

    $idDoctor = $_SESSION['usuario']['doctor_id'];
    $idEspecialidad = ($_POST['especialidad_id'] ?? 0);

    $seleccion = [];
    foreach (($_POST['dias'] ?? []) as $dia) {
        if ($dia < 0 || $dia > 5) continue;

        $hora = $_POST['horas'][$dia] ?? '';
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) continue;

        $seleccion[$dia] = $hora;
    }

    $conexion->begin_transaction();
    try {
        if ($seleccion) {
            $marcas = implode(',', array_fill(0, count($seleccion), '?'));
            $stmt = $conexion->prepare(
                "DELETE FROM agenda_fija
                 WHERE doctor_id = ? AND especialidad_id = ? AND dia NOT IN ($marcas)"
            );
            $stmt->bind_param('ii' . str_repeat('i', count($seleccion)), $idDoctor, $idEspecialidad, ...array_keys($seleccion));
        } else {
            $stmt = $conexion->prepare(
                "DELETE FROM agenda_fija WHERE doctor_id = ? AND especialidad_id = ?"
            );
            $stmt->bind_param('ii', $idDoctor, $idEspecialidad);
        }
        $stmt->execute();
        $stmt->close();

        $stmt = $conexion->prepare(
            "INSERT INTO agenda_fija (doctor_id, especialidad_id, dia, hora_inicio)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE hora_inicio = VALUES(hora_inicio)"
        );
        foreach ($seleccion as $dia => $hora) {
            $stmt->bind_param('iiis', $idDoctor, $idEspecialidad, $dia, $hora);
            $stmt->execute();
        }
        $stmt->close();

        $conexion->commit();
    } catch (Throwable $e) {
        $conexion->rollback();
    }

    header("Location: " . base_url);
    exit();