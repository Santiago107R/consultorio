<?php
    require_once './layouts/header.php';
    require_once __DIR__ . '/../config/conexion.php';

    $estadoFiltro = $_GET['estado'] ?? '';
    $orden = match ($_GET['orden'] ?? 'asc') {
        'desc'  => 't.fecha DESC, t.hora DESC',
        default => 't.fecha ASC, t.hora ASC',
    };

    $sql = "
        SELECT
            t.id,
            t.fecha,
            t.hora,
            up.nombre                   AS paciente,
            p.dni,
            ud.nombre                   AS doctor,
            COALESCE(e.nombre, s.nombre) AS motivo,
            cons.nombre                 AS consultorio,
            es.nombre                   AS estado
        FROM turnos AS t
        INNER JOIN pacientes  AS p   ON p.id   = t.paciente_id
        INNER JOIN usuarios  AS up  ON up.id  = p.usuario_id
        LEFT  JOIN especialidades    AS e   ON e.id   = t.especialidad_id
        LEFT  JOIN servicios         AS s   ON s.id   = t.servicio_id
        LEFT JOIN doctores    AS d   ON d.id   = t.doctor_id
        LEFT JOIN usuarios  AS ud  ON ud.id  = d.usuario_id
        LEFT  JOIN consultorios      AS cons ON cons.id = t.consultorio_id
        INNER JOIN estados   AS es  ON es.id  = t.estado_id
    ";

    if ($estadoFiltro !== '') {
        $sql .= " WHERE es.nombre = ?";
    }
    $sql .= " ORDER BY $orden";   

    $stmt = $conexion->prepare($sql);
    if ($estadoFiltro !== '') {
        $stmt->bind_param('s', $estadoFiltro);
    }
    $stmt->execute();
    $turnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    function url_filtro(array $cambios): string {
        $params = array_merge($_GET, $cambios);
        $params = array_filter($params, fn($v) => $v !== null && $v !== '');
        return 'index.php' . ($params ? '?' . http_build_query($params) : '');
    }
    
    function clase_filtro(string $clave, ?string $valor): string {
        $activo = ($_GET[$clave] ?? null) === $valor;
        return $activo
            ? 'bg-(--color-paciente-primary) text-white rounded-lg px-2'
            : 'bg-white hover:bg-(--color-paciente-primary) hover:text-white rounded-lg px-2';
    }
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 font-medium">

    <div class="w-full flex flex-row justify-between text-gray-800">
        <h2>Turnos (<?= count($turnos) ?>)</h2>

        <div class="flex flex-row gap-2 items-center">
            <ion-icon name="funnel"></ion-icon>

            <a href="<?= url_filtro(['estado' => null]) ?>" class="<?= clase_filtro('estado', null) ?>">Todos</a>
            <a href="<?= url_filtro(['estado' => 'Pendiente']) ?>" class="<?= clase_filtro('estado', 'Pendiente') ?>">Pendientes</a>
            <a href="<?= url_filtro(['estado' => 'Aprobado']) ?>" class="<?= clase_filtro('estado', 'Aprobado') ?>">Aprobados</a>
            <a href="<?= url_filtro(['estado' => 'Cancelado por la clinica']) ?>" class="<?= clase_filtro('estado', 'Cancelado por la clinica') ?>">Cancelado por la clinica</a>

            <a href="<?= url_filtro(['orden' => 'asc']) ?>" class="<?= clase_filtro('orden', 'asc') ?>">Ascendiente</a>
            <a href="<?= url_filtro(['orden' => 'desc']) ?>" class="<?= clase_filtro('orden', 'desc') ?>">Descendiente</a>
        </div>
    </div>

    <div id="tabla_body" class="min-h-90 grid grid-cols-1 overflow-y-auto rounded-lg text-sm">
        <table class="w-full border-separate border-spacing-0 text-center">
            <thead class="sticky top-0 z-10">
                <tr>
                    <th class="py-2 bg-slate-200 rounded-tl-lg">HORARIO / CONSULTORIO</th>
                    <th class="py-2 bg-slate-200">PACIENTE</th>
                    <th class="py-2 bg-slate-200">PROFESIONAL & MOTIVO</th>
                    <th class="py-2 bg-slate-200">ESTADO</th>
                    <th class="py-2 bg-slate-200 rounded-tr-lg">ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($turnos)): ?>
                    <tr>
                        <td colspan="5" class="py-8 bg-white rounded-b-lg text-gray-500">No hay turnos para mostrar</td>
                    </tr>
                <?php endif; ?>
                
                <?php $indiceTurno = 0; ?>
                <?php foreach ($turnos as $turno): ?>
                    <tr class="group h-20">
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0 group-last:rounded-bl-lg">
                            <div class="flex flex-col">
                                <span><?= date('d/m/Y', strtotime($turno['fecha'])) ?></span>
                                <span><?= substr($turno['hora'], 0, 5) ?>hs / <?= $turno['consultorio'] ?? 'Sin asignar' ?></span>
                            </div>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0">
                            <div class="flex flex-col">
                                <span><?= $turno['paciente'] ?></span>
                                <span>DNI: <?= $turno['dni'] ?></span>
                            </div>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0">
                            <div class="flex flex-col">
                                <span><?= $turno['doctor'] ?></span>
                                <span><?= $turno['motivo'] ?? '-' ?></span>
                            </div>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0">
                            <span class="<?= badge_status_classes[$turno['estado']] ?? '' ?> rounded-lg px-2">
                                <?= $turno['estado'] ?>
                            </span>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0 group-last:rounded-br-lg">
                           <div class="relative flex items-center justify-center">
                                <button onclick="toggleMenu(event, 'menu-<?= $indiceTurno ?>')" class="cursor-pointer p-1 rounded-full hover:bg-gray-200 transition-colors flex items-center justify-center">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none"></ion-icon>
                                </button>
                                <div id="menu-<?= $indiceTurno ?>" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-32 py-1">
                                    <ul class="flex flex-col text-left">
                                        <?php if (empty($turno['consultorio'])): ?>
                                            <a href="asignarConsultorio.php?id=<?= $turno['id'] ?>">
                                                <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Asignar Consultorio</li>
                                            </a>
                                        <?php else: ?>
                                                <a href="cambiarEstado.php?id=<?= $turno['id'] ?>&estado=7">
                                                    <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Aprobar</li>
                                                </a>

                                                <a href="cambiarEstado.php?id=<?= $turno['id'] ?>&estado=5">
                                                    <li class="list-none px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer text-sm">Cancelar</li>
                                                </a>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php $indiceTurno++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
    function toggleMenu(event, menuId) {
        event.stopPropagation();

        const menu = document.getElementById(menuId);
        const isCurrentlyHidden = menu.classList.contains('hidden');

        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));

        if (isCurrentlyHidden) {
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();

            menu.style.top = `${rect.top + (rect.height / 2) - 16}px`;
            menu.style.left = `${rect.left - 135}px`;

            menu.classList.remove('hidden');
        }
    }

    const tabla_body = document.getElementById('tabla_body');

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });

    tabla_body.addEventListener('scroll', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });


</script>