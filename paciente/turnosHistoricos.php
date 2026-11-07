<?php
    // require_once './layouts/header.php';
    require_once './layouts/datosTurnos.php';
    // require_once __DIR__ . '/../config/conexion.php';

    $practica = $_GET['filtro'] ?? '';

    $paciente_id = $_SESSION['usuario']['paciente_id'];

    if ($practica === "especialidades") {
        $query = "SELECT t.id, t.fecha, t.hora, e.nombre AS especialidad, u.nombre AS doctor, es.nombre AS estado
                  FROM turnos_historicos AS t
                  INNER JOIN especialidades AS e ON e.id = t.especialidad_id
                  INNER JOIN doctores AS d ON d.id = t.doctor_id
                  INNER JOIN usuarios AS u ON u.id = d.usuario_id
                  INNER JOIN estados AS es ON es.id = t.estado_id
                  WHERE t.paciente_id = ?";
    } elseif ($practica === "servicios") {
        $query = "SELECT t.id, t.fecha, t.hora, s.nombre AS servicio, es.nombre AS estado
                  FROM turnos_historicos AS t
                  INNER JOIN servicios AS s ON s.id = t.servicio_id
                  INNER JOIN estados AS es ON es.id = t.estado_id
                  WHERE t.paciente_id = ?";
    } else {
        $query = "SELECT t.id, t.fecha, t.hora, e.nombre AS especialidad, s.nombre AS servicio, u.nombre AS doctor, es.nombre AS estado
                  FROM turnos_historicos AS t
                  LEFT JOIN especialidades AS e ON e.id = t.especialidad_id
                  LEFT JOIN servicios AS s ON s.id = t.servicio_id
                  LEFT JOIN doctores AS d ON d.id = t.doctor_id
                  LEFT JOIN usuarios AS u ON u.id = d.usuario_id
                  INNER JOIN estados AS es ON es.id = t.estado_id
                  WHERE t.paciente_id = ?";
    }

    // $paciente_id = $_SESSION['usuario']['paciente_id'];
    // $query = "SELECT t.id, t.fecha, t.hora, e.nombre AS especialidad, s.nombre AS servicio, u.nombre AS doctor, es.nombre AS estado FROM turnos_historicos AS t LEFT JOIN especialidades AS e ON e.id = t.especialidad_id LEFT JOIN servicios AS s ON s.id = t.servicio_id INNER JOIN doctores AS d ON d.id = t.doctor_id INNER JOIN usuarios AS u ON u.id = d.usuario_id INNER JOIN estados AS es ON es.id = t.estado_id WHERE t.paciente_id = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $paciente_id);
    $stmt->execute();
    $turnosHistoricos = $stmt->get_result();
    $stmt->close();

    $numRows = $turnosHistoricos->num_rows
?>


    <div class="w-full flex items-center justify-between text-gray-800 text-sm">
        <div class="flex flex-row gap-2 bg-slate-200 rounded-lg p-1">
            <a href="index.php">
                 <button id="proximos-turnos" class="hover:bg-(--color-paciente-white) hover:text-(--color-paciente-primary) rounded-md p-2">
                    <span class="flex items-center gap-2">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Próximos Turnos
                        <!-- (2) -->
                    </span>
                </button>
            </a>    
                <button id="historial-turnos" class="bg-(--color-paciente-white) text-(--color-paciente-primary) rounded-md p-2">
                    <span class="flex items-center gap-2">
                        <ion-icon name="newspaper-outline"></ion-icon>
                        Historial Completo
                        <!-- (3) -->
                    </span>
                </button>
        </div>
        
        <div id="filtrar-proximos" class="flex flex-row items-center justify-center gap-2 text-gray-700">
            <span class="uppercase">filtrar:</span>
            <a href="turnosHistoricos.php">
                <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                    Todos los tipos
                </button>
            </a>
            <a href="turnosHistoricos.php?filtro=especialidades">
                <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                    Consultas médicas
                </button>
            </a>
            <a href="turnosHistoricos.php?filtro=servicios">
                <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                    Servicios
                </button>
            </a>
        </div>
    </div>

    <div class="w-full h-96 grid grid-cols-1 gap-4 overflow-y-auto p-4 text-sm text-gray-800 border border-slate-200 rounded-lg scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-100 hover:scrollbar-thumb-slate-400 scrollbar-thumb-rounded-full">

        <?php if ($numRows > 0): ?>
            <?php while ($turnoHistorico = $turnosHistoricos->fetch_assoc()): ?>
                <div class="bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg">
                    <div class="flex flex-row items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 leading-relaxed">
                                <span class="bg-slate-100 rounded-md text-xs py-0.5 px-2">Consulta Médica de <?= !empty($turnoHistorico['especialidad']) ? "Especialidad" : "Servicio" ?></span>
                                <span class="rounded-md py-0.5 px-2 text-xs font-medium <?= badge_status_classes[$turnoHistorico['estado']] ?>"><?= $turnoHistorico['estado'] ?></span>
                            </div>
                            <div>
                                <div>
                                    <h2 class="text-xl leading-relaxed"><?= !empty($turnoHistorico['especialidad']) ? $turnoHistorico['especialidad'] : $turnoHistorico['servicio'] ?></h2>
                                        <?php if (isset($turnoHistorico['doctor'])): ?>
                                            <span class="text-base">Dr. <?= $turnoHistorico['doctor'] ?></span>
                                        <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-base text-(--color-paciente-primary)">
                            <ion-icon name="calendar-clear-outline"></ion-icon>
                            <span><?= formatearFecha($turnoHistorico['fecha']) ?> • <?= $turnoHistorico['hora'] ?> hs</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <div class="flex flex-row gap-4">
                            <button id="reprogramar-turno" class="flex items-center gap-2 bg-slate-200 hover:bg-slate-300 rounded-md p-2">
                                <ion-icon name="reload-outline"></ion-icon>
                                <span>Reprogramar</span>
                            </button>
                            <button id="cancelar-turno" class="flex items-center gap-2 text-red-500 hover:bg-red-50 rounded-md p-2">
                                <ion-icon name="close-circle-outline"></ion-icon>
                                <span>Cancelar Turno</span>
                            </button>
                        </div>
                    </div>
                </div>

                <?php if ($numRows == 1): ?>
                    <div class="invisible bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg"></div>
                    <div class="invisible bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg"></div>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg">
                <div class="flex justify-center">
                    <span class="text-xl">No tienes turnos historicos</span>
                </div>
            </div>
            <div class="invisible bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg"></div>
            <div class="invisible bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg"></div>
        <?php endif; ?>
    </div>

</main>