<?php
    require_once './layouts/header.php';
    require_once __DIR__ . '/../../config/conexion.php';

    $paciente_id = $_SESSION['usuario']['paciente_id'];

    $queryProximo = "SELECT t.id, t.fecha, t.hora, e.nombre AS especialidad, s.nombre AS servicio, u.nombre AS doctor
                     FROM turnos AS t
                     LEFT JOIN especialidades AS e ON e.id = t.especialidad_id
                     LEFT JOIN servicios AS s ON s.id = t.servicio_id
                     INNER JOIN doctores AS d ON d.id = t.doctor_id
                     INNER JOIN usuarios AS u ON u.id = d.usuario_id
                     WHERE t.paciente_id = ?
                       AND t.estado_id NOT IN (1, 4, 5, 6)
                       AND t.fecha >= CURDATE()
                     ORDER BY t.fecha ASC, t.hora ASC
                     LIMIT 1";

    $stmtProximo = $conexion->prepare($queryProximo);
    $stmtProximo->bind_param('i', $paciente_id);
    $stmtProximo->execute();
    $proximoTurno = $stmtProximo->get_result()->fetch_assoc();
    $stmtProximo->close();

    $practica = '';
    if ($proximoTurno) {
        $practica = !empty($proximoTurno['especialidad']) ? $proximoTurno['especialidad'] : $proximoTurno['servicio'];
    }

    $queryTurno = "SELECT COUNT(id) AS totalTurnos FROM turnos WHERE paciente_id = ?";

    $stmtTurno = $conexion->prepare($queryTurno);
    $stmtTurno->bind_param('i', $paciente_id);
    $stmtTurno->execute();
    $totalTurnos = $stmtTurno->get_result()->fetch_assoc()['totalTurnos'];
    $stmtTurno->close();

    $queryHistorico = "SELECT COUNT(id) AS totalTurnosHistoricos FROM turnos_historicos WHERE paciente_id = ?";

    $stmtHistorico = $conexion->prepare($queryHistorico);
    $stmtHistorico->bind_param('i', $paciente_id);
    $stmtHistorico->execute();
    $totalTurnosHistoricos = $stmtHistorico->get_result()->fetch_assoc()['totalTurnosHistoricos'];
    $stmtHistorico->close();

    $queryUltimaHistorica = "SELECT t.fecha, e.nombre AS especialidad, s.nombre AS servicio
                             FROM turnos_historicos AS t
                             LEFT JOIN especialidades AS e ON e.id = t.especialidad_id
                             LEFT JOIN servicios AS s ON s.id = t.servicio_id
                             WHERE t.paciente_id = ?
                             ORDER BY t.fecha DESC, t.hora DESC
                             LIMIT 1";

    $stmtUltima = $conexion->prepare($queryUltimaHistorica);
    $stmtUltima->bind_param('i', $paciente_id);
    $stmtUltima->execute();
    $ultimoTurnoHistorico = $stmtUltima->get_result()->fetch_assoc();
    $stmtUltima->close();

    $ultimaPractica = '';
    if ($ultimoTurnoHistorico) {
        $ultimaPractica = !empty($ultimoTurnoHistorico['especialidad']) ? $ultimoTurnoHistorico['especialidad'] : $ultimoTurnoHistorico['servicio'];
    }
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 font-medium">
    
    <div class="w-full flex items-end justify-between">
        
        <div class="max-w-3xl">
            <h2 class="mb-2 text-2xl font-semibold text-black">
                Hola, 
                <?= htmlspecialchars(
                    $_SESSION['usuario']['nombre'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>
            
            <p class="text-base font-medium leading-relaxed text-gray-700">
                Gestioná tus citas programadas, consultá los requerimientos previos y descargá tus 
                informes diagnósticos certificados en formato digital.
            </p>
        </div>

        <button class="flex items-center justify-center px-4 py-2 text-sm font-medium transition-colors rounded-md bg-(--color-paciente-primary) text-(--color-paciente-white) hover:opacity-90 whitespace-nowrap">
            <a href="sacarTurno.php">
                <span class="flex items-center justify-center gap-2">
                    <ion-icon class="size-5" name="add-circle-outline"></ion-icon> 
                    Solicitar Nuevo Turno
                </span>
            </a>    
        </button>
    </div>

   <div class="w-full grid grid-cols-3 gap-4">

        <div class="h-40 flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4 overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">PRÓXIMO TURNO</span>
                <ion-icon name="today-outline" class="text-sm text-(--color-paciente-primary) bg-sky-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col min-w-0">
                <?php if ($proximoTurno): ?>
                    <h2 class="text-xl text-(--color-paciente-primary) font-medium leading-relaxed truncate"><?= formatearFecha($proximoTurno['fecha']) ?></h2>
                    <p class="text-sm text-gray-800 font-medium leading-loose truncate"><?= substr($proximoTurno['hora'], 0, 5) ?>hs - <?= htmlspecialchars($practica) ?></p>
                    <p class="text-xs truncate">Dr. <?= htmlspecialchars($proximoTurno['doctor']) ?></p>
                <?php else: ?>
                    <h2 class="text-xl text-gray-800 font-medium leading-relaxed">Sin turnos próximos</h2>
                <?php endif; ?>
            </div>
        </div>

        <div class="h-40 flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4 overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">TURNOS HISTÓRICOS</span>
                <ion-icon name="checkmark-done-outline" class="text-sm text-(--color-paciente-badge-text) bg-sky-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col min-w-0">
                <h2 class="text-xl text-gray-800 font-medium leading-relaxed truncate"><?= $totalTurnosHistoricos ?> Atenciones</h2>
                <?php if ($ultimoTurnoHistorico): ?>
                    <p class="text-sm text-gray-800 font-medium leading-loose truncate">Última: <?= formatearFecha($ultimoTurnoHistorico['fecha']) ?> (<?= htmlspecialchars($ultimaPractica) ?>)</p>
                <?php else: ?>
                    <p class="text-sm text-gray-800 font-medium leading-loose">Sin atenciones previas</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="h-40 flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4 overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">PRÓXIMOS TURNOS</span>
                <ion-icon name="document-text-outline" class="text-sm text-gray-800 bg-teal-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col min-w-0">
                <h2 class="text-xl text-(--color-paciente-primary) font-medium leading-relaxed truncate"><?= $totalTurnos ?> Disponibles</h2>
            </div>
        </div>

    </div>